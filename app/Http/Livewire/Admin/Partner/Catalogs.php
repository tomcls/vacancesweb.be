<?php

namespace App\Http\Livewire\Admin\Partner;

use Livewire\Component;
use App\Models\Partner;
use App\Models\HolidayTitle;
use App\Models\PartnerCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;

class Catalogs extends Component
{
    use  WithSorting, WithBulkActions, WithCachedRows;

    public PartnerCatalog $editing;

    public $showDeleteModal = false;
    public $showEditModal = false;

    public $partners;
    protected $queryString = ['sorts'];

    public $holidays = null;
    public $holidaySearch = null;
    public $holidayId = null;

    public $lang;

    protected $listeners = ['selectAutoCompleteItem' => 'setAutoCompleteItem', 'reorder' => 'reorder'];

    public $showFilters = false;

    public $rules = [
        'editing.partner_id' => 'required',
        'editing.holiday_id' => 'required',
        'editing.lang' => 'required',
        'editing.sort' => 'sometimes',
    ];
    public $filters = [
        "id" => null,
        "partner_id" => null,
        "holiday_id" => null,
        'lang' => null,
    ];

    public function mount()
    {
        $this->editing = $this->makeBlankBox();
        $this->partners = Partner::get();
        $this->lang = App::currentLocale();
    }
    public function makeBlankBox()
    {
        return PartnerCatalog::make([]);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' catalog items','type'=>'success']);
    }

    public function getRowsQueryProperty()
    {
        $query = PartnerCatalog::query()
        ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id))
        ->when($this->filters['lang'], fn ($query, $lang) => $query->where('lang', '=', $lang))
        ->when($this->filters['partner_id'], fn ($query, $id) => $query->where('partner_id', '=', $id))
        ->when($this->filters['holiday_id'], fn ($query, $id) => $query->where('holiday_id', '=', $id));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->get();
    }
    public function holidaysResult()
    {
        if ($this->holidaySearch) {
            $query = HolidayTitle::query()->select(DB::raw("holiday_id as id, name AS title, slug as subtitle"))
                ->when($this->holidaySearch, fn ($query, $name) => $query
                    ->where('name', 'like', '%' . $name . '%')
                    ->orWhere('holiday_id', 'like', '%' . $name . '%'))->limit(5);
            $this->holidays = $query->get();
        }
    }
    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankBox();

        $this->showEditModal = true;
    }

    public function edit(PartnerCatalog $item)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($item)) {
            $this->editing = $item;
            $this->holidaySearch = $this->editing->holiday_id;
        }
        $this->showEditModal = true;
    }

    public function setAutoCompleteItem($type, $text, $id)
    {
        $holiday = HolidayTitle::whereHolidayId($id)->whereLang($this->lang)->first();

        $this->editing->holiday_id = $id;
        $this->holidaySearch = $holiday->holiday->id . '# ' . $holiday->name;
    }
    public function reorder($orderedIds)
    {
        collect($orderedIds)->map(function ($id, $key) {
            return PartnerCatalog::whereId($id)->update(['sort' => $key]);
        });
    }
    public function save()
    {
        $h = PartnerCatalog::whereLang($this->editing->lang)->whereHolidayId($this->editing->holiday_id)->wherePartnerId($this->editing->partner_id)->first();
        if ($h && $h->id) {
            return $this->notify(['message' => 'Catalog item already exist', 'type' => 'alert']);
        }
        $this->validate();
        $total = PartnerCatalog::wherePartnerId($this->editing->partner_id)->whereLang($this->editing->lang)->count();
        $this->editing->sort = $total;
        $this->editing->save();

        $this->showEditModal = false;

        $this->notify(['message' => 'Catalog item well saved', 'type' => 'success']);
        $this->emit('initDragAndDrop');
    }

    public function render()
    {
        return view('livewire.admin.partner.catalogs', [
            'catalogs' => $this->rows,
        ])->layout('layouts.admin');
    }
}
