<?php

namespace App\Http\Livewire\Admin\House;

use Livewire\Component;

use App\Traits\DataTable\WithPerPagePagination;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithCachedRows;
use App\Models\HousePackageTranslation;
use App\Traits\DataTable\WithSorting;
use Illuminate\Support\Facades\App;
use App\Models\HousePackage;

class Packages extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public HousePackage $editing;
    public $lang = null;
    protected $queryString = ['sorts'];

    public $titles = [];

    public $filters = [
        'lang' => null,
        'id' => null,
        'code' => null,
        'name' => null,
        'price' => null,
        'total' => null,
    ];

    public $rules = [
        "editing.id" => 'sometimes',
        "editing.code" => 'required',
        "editing.price" => 'required',
        "editing.total" => 'required',
        "titles.*.name" => 'sometimes'
    ];
    protected $listeners = [
        'setPackageName' => 'setTitle',
        'refreshComponent' => '$refresh'
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public function mount()
    {

        $this->editing = $this->makeBlankPackage();

        $this->lang = $this->filters['lang'] = App::currentLocale();

        collect(config('app.langs'))->map(function ($lang) {
            $this->titles[$lang] = $this->makeBlankPackageTranslation($lang);
        });
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' package(s)', 'type' => 'success']);
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankPackage();

        $this->showEditModal = true;
    }

    public function edit(HousePackage $housePackage)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($housePackage)) {
            $this->editing = $housePackage;
            foreach ($this->titles as $lang => $title) {
                $this->titles[$lang] = HousePackageTranslation::whereHousePackageId($housePackage->id)->whereLang($lang)->first();
            }
        } 

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {

                if ($this->packageNameIsInvalid()) {
                    $validator->errors()->add('title.name', 'The name should be filled in');
                    $this->notify(['message' => 'The name should be filled in', 'type' => 'alert']);
                }
            });
        })->validate();

        if($this->editing->id) {
            $this->editing->update();
        } else {
            $this->editing->save();
        }
        foreach ($this->titles as $lang => $title) {
            $housePackageTranslation = HousePackageTranslation::whereHousePackageId($this->editing->id)->whereLang($lang)->first();
            if($housePackageTranslation) {
                $translation = new HousePackageTranslation([
                    'id' => $housePackageTranslation->id,
                    'house_package_id' => $this->editing->id,
                    'lang' => $lang,
                    'name' => $title['name']
                ]);
                $translation->update();
            } else {
                $translation = new HousePackageTranslation([
                    'house_package_id' => $this->editing->id,
                    'lang' => $lang,
                    'name' => $title['name']
                ]);
                $translation->save();
            }
        }
       // $this->emit("savepackage",$this->editing->id);

       

        $this->showEditModal = false;

        $this->notify(['message' => 'Package well saved', 'type' => 'success']);
        
        
    }

    public function makeBlankPackageTranslation($lang)
    {
        return new HousePackageTranslation(['lang' => $lang, 'name' => '']);
    }

    public function makeBlankPackage()
    {
        return HousePackage::make([]);
    }

    public function packageNameIsInvalid()
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
        $query = HousePackageTranslation::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id))
            ->when($this->filters['price'], fn ($query, $price) => $query->where('price', '=', $price))
            ->when($this->filters['total'], fn ($query, $total) => $query->where('total', '=', $total))
            ->when($this->filters['name'], fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'))
            ->when($this->filters['code'], fn ($query, $name) =>
                $query->whereHas('package', fn ($query) => $query->where('code', 'like', '%' . $name . '%')))
            ->whereLang($this->lang);
        return $this->applySorting($query);
    }
    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        // return $this->cache(function () {
        //     return $this->applyPagination($this->rowsQuery);
        // });
    }

    public function setTitle($lang, $title)
    {
        $this->titles[$lang]['name'] = $title;
    }

    public function render()
    {
        return view('livewire.admin.house.packages', [
            'packages' => $this->rows,
        ])->layout('layouts.admin');
    }
}
