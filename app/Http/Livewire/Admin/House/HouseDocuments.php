<?php

namespace App\Http\Livewire\Admin\House;

use App\Traits\DataTable\WithPerPagePagination;
use App\Models\HouseDocumentTranslation;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithSorting;
use Illuminate\Support\Facades\App;
use Livewire\WithFileUploads;
use App\Models\HouseDocument;
use Illuminate\Support\Str;
use Livewire\Component;
use Exception;
use File;
use Illuminate\Contracts\Validation\Validator;

class HouseDocuments extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows, WithFileUploads;

    public HouseDocument $editing;
    public $houseId;
    public $upload;
    public $lang;
    public $langForEditing;
    protected $queryString = ['sorts'];

    public $titles = [];

    public $filters = [
        'name' => null,
        'lang' => null,
        'origin' => null,
    ];

    public $rules = [
        "editing.name" => 'required',
        "editing.origin" => 'required',
    ];
    protected $listeners = [
        'setDocumentName' => 'setTitle',
        'refreshComponent' => '$refresh'
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public function mount($houseId)
    {

        $this->houseId = $houseId;
        $this->editing = $this->makeBlankDocument();

        $this->lang = $this->filters['lang'] =  App::currentLocale();
        $this->langForEditing = $this->lang;

        collect(config('app.langs'))->map(function ($lang) {
            $this->titles[$lang] = $this->makeBlankDocumentTranslation($lang);
        });
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' seasons', 'type' => 'success']);
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankDocument();

        $this->showEditModal = true;
    }

    public function edit(HouseDocument $houseDocument)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($houseDocument)) {
            $this->editing = $houseDocument;
            $this->langForEditing = $this->lang;
            $this->titles[$this->lang] = HouseDocumentTranslation::whereHouseDocumentId($houseDocument->id)->whereLang($this->lang)->first() ?? $this->makeBlankDocumentTranslation($this->lang);
        }

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->editing->house_id = $this->houseId;
        $name = null;
        if ($this->upload) {

            $typeExplode = explode('.', $this->upload->getClientOriginalName());
            $type = $typeExplode[count($typeExplode) - 1];
            $name = Str::random(30) . '.' . $type;

            $destinationPathdocuments = storage_path('app/houses/documents');
            $this->notify(['message' => 'Start processing documents(s)... please wait', 'type' => 'alert']);
            try {
                File::makeDirectory($destinationPathdocuments, 0777, false, false);
            } catch (Exception $e) {}

            $destinationPath = storage_path('app/houses/documents') . '/' . $this->houseId;
            try {
                File::makeDirectory($destinationPath, 0777, false, false);
            } catch (Exception $e) {}

            $this->editing->name = $name;
            $this->editing->origin = $this->upload->getClientOriginalName();
        }
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {

                if ($this->documentNameIsInvalid()) {
                    $validator->errors()->add('houseDocument.name', 'The name should be filled in');
                    $this->notify(['message' => 'The name should be filled in', 'type' => 'alert']);
                }
            });
        })->validate();

        $this->editing->save();

        foreach ($this->titles as $lang => $title) {
            $houseDocumentTranslation = HouseDocumentTranslation::whereHouseDocumentId($this->editing->id)->whereLang($lang)->first();
            if($houseDocumentTranslation) {
                $translation = new HouseDocumentTranslation([
                    'id' => $houseDocumentTranslation->id,
                    'house_document_id' => $this->editing->id,
                    'lang' => $lang,
                    'name' => $title['name']
                ]);
                $translation->update();
            } else {
                $translation = new HouseDocumentTranslation([
                    'house_document_id' => $this->editing->id,
                    'lang' => $lang,
                    'name' => $title['name']
                ]);
                $translation->save();
            }
        }

        if ($this->upload && $name) {
            $this->upload->storeAs('houses/documents/' . $this->houseId, $name);
        }

        $this->showEditModal = false;

        $this->notify(['message' => 'Document price well saved', 'type' => 'success']);
        //return redirect(request()->header('Referer'));
       // $this->houseDocuments->refresh();
    }

    public function makeBlankDocumentTranslation($lang)
    {
        return new HouseDocumentTranslation(['lang' => $lang, 'name' => '']);
    }

    public function makeBlankDocument()
    {
        return HouseDocument::make([]);
    }

    public function documentNameIsInvalid()
    {
        foreach ($this->titles as $title) {
            if ($title['name']) return false;
        }
        return true;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function toggleShowFilters()
    {
        //$this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function getRowsQueryProperty()
    {
        $query = HouseDocumentTranslation::query()
            ->when($this->filters['name'], fn ($query, $name) =>
            $query->whereHas('document', fn ($query) => $query->where('name', 'like', '%' . $name . '%')))
            ->when($this->filters['origin'], fn ($query, $origin) =>
            $query->whereHas('document', fn ($query) => $query->where('origin', 'like', '%' . $origin . '%')));
            $query->whereHas('document', fn ($query) => $query->whereHouseId($this->houseId))
            ->whereLang($this->filters['lang'] ?? App::currentLocale());
        return $this->applySorting($query);
    }

    public function setTitle($lang, $title)
    {
        $this->titles[$lang]['name'] = $title;
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        // return $this->cache(function () {
        //     return $this->applyPagination($this->rowsQuery);
        // });
    }
    public function render()
    {
        return view('livewire.admin.house.house-documents', [
            'documents' => $this->rows,
        ]);
    }
}
