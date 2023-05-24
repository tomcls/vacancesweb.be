<?php

namespace App\Http\Livewire\Admin\Holiday;

use App\Models\HolidayPrice;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithPerPagePagination;
use App\Traits\DataTable\WithSorting;
use Livewire\Component;

class HolidayPrices extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;


    public $filters = [
        'id' => null,
        'departure-date' => null,
        'departure-from' => null,
        'duration-days' => null,
        'duration-nights' => null,
        'price' => null,
        'price-customer' => null,
        'discount' => null,
        'lowest-price' => null,
        'info' => null,
    ];
    public HolidayPrice $editing;

    protected $queryString = ['sorts'];

    public $holidayId;
    public $priceSearch = null;

    public function rules()
    {
        return [
            'editing.departure_date_for_editing' => 'required',
            'editing.departure_from' => 'required',
            'editing.duration_days' => 'required|max:4',
            'editing.duration_nights' => 'required|max:4',
            'editing.price' => 'required',
            'editing.price_customer' => 'required',
            'editing.discount' =>  'sometimes',
            'editing.info' =>  'required',
        ];
    }
    public function mount($holidayId)
    {
        $this->holidayId = $holidayId;
        $this->editing = $this->makeBlankPrice();
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count()) {
            return response()->streamDownload(function () {
                echo $this->selectedRowsQuery->toCsv();
            }, 'prices.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->setLowestPrice();

        $this->notify('You\'ve deleted ' . $deleteCount . ' prices');
    }

    public function makeBlankPrice()
    {
        return HolidayPrice::make([]);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankPrice();

        $this->showEditModal = true;
    }

    public function edit(HolidayPrice $price)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($price)) $this->editing = $price;

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->editing->holiday_id = $this->holidayId;
        $this->validate();
        $this->editing->save();

        $this->setLowestPrice();

        $this->showEditModal = false;

        $this->notify(['message'=>'Holiday price well saved','type'=>'alert']);
    }
    public function setLowestPrice() {
        HolidayPrice::whereHolidayId($this->holidayId)->update(['lowest_price'=>0]);
        $lowestPrice = HolidayPrice::whereHolidayId($this->holidayId)->where('departure_date','>=', date("Y-m-01"))->orderBy('price_customer','asc')->first();
        $lowestPrice->lowest_price = 1;
        $lowestPrice->update();
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = HolidayPrice::query()->whereHolidayId($this->holidayId)
            ->when($this->filters['departure-date'], fn ($query, $date) => $query->where('departure_date', '=', $date))
            ->when($this->filters['departure-from'], fn ($query, $from) => $query->where('departure_from', '=', $from))
            ->when($this->filters['duration-days'], fn ($query, $days) => $query->where('duration_days', '=', $days))
            ->when($this->filters['duration-nights'], fn ($query, $nights) => $query->where('departure_nights', '=', $nights))
            ->when($this->filters['price'], fn ($query, $price) => $query->where('price', '=', $price))
            ->when($this->filters['price-customer'], fn ($query, $price) => $query->where('price-customer', '=', $price))
            ->when($this->filters['discount'], fn ($query, $discount) => $query->where('discount', '=', $discount))
            ->when($this->filters['info'], fn ($query, $info) => $query->where('discount', '=', $info));
        $p =    $this->applySorting($query);
        

        return $p;
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        /* return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });*/
    }

    public function render()
    {
        return view('livewire.admin.holiday.holiday-prices', [
            'prices' => $this->rows,
        ])->layout('layouts.admin');
    }

}
