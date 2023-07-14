<?php

namespace App\Http\Livewire\Admin\House;

use App\Models\House;
use Livewire\Component;
use App\Models\HouseIcal;
use Illuminate\Support\Carbon;
use App\Models\HouseReservation;
use App\Models\HouseTitle;
use Illuminate\Support\Facades\DB;
use App\Repositories\IcalRepository;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Icals extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public HouseIcal $editing;


    public $houses = null;
    public $houseSearch = null;

    protected $queryString = ['sorts'];

    public $filters = [
        'id' => null,
        'user_id' => null,
        'house_id' => null,
        'created-min' => null,
        'created-max' => null,
    ];
    protected $listeners = [
        'selectAutoCompleteItem' => 'setAutoCompleteItem',
    ];

    public $rules = [
        "editing.house_id" => 'required',
        "editing.url" => 'required',
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public function mount()
    {
        $this->editing = $this->makeBlankIcal();
    }

    public function makeBlankIcal()
    {
        return HouseIcal::make([]);
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

        if ($this->editing->getKey()) $this->editing = $this->makeBlankIcal();

        $this->showEditModal = true;
    }

    public function edit(HouseIcal $houseIcal)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($houseIcal)) {
            $this->houseSearch = $houseIcal->house_id;
            $this->editing = $houseIcal;
        }
        $this->showEditModal = true;
    }

    public function save()
    {
        // if no invoice_id then create an invoice else just create a new transaction
        $this->validate();
        $this->editing->hash = md5(microtime());
        $iCal = new IcalRepository($this->editing->url);
        $events = $iCal->eventsByDate();

        HouseReservation::whereHouseId($this->editing->house_id)->delete();
        $user_id = House::whereId($this->editing->house_id)->first()->user->id;

        if (!empty($events) && count($events) > 0) {
            foreach ($events as $date => $events) {
                foreach ($events as $event) {
                    if ($event->timeStart() != null && $event->timeEnd()  != 0) {
                        $start_date = date('Y-m-d H:i:s', $event->timeStart());
                        $end_date = date('Y-m-d H:i:s', $event->timeEnd());
                        $resa = new HouseReservation();
                        $resa->house_id = $this->editing->house_id;
                        $resa->user_id = $user_id;
                        $resa->startdate =  $start_date;
                        $resa->enddate = $end_date;
                        $resa->save();
                    }
                }
            }
            $this->editing->save();
            $this->notify(['message' => 'Ical well set and well sync', 'type' => 'success']);
        } else {
            $this->notify(['message' => 'No event retrieved', 'type' => 'alert']);
        }


        $this->showEditModal = false;
    }


    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function getRowsQueryProperty()
    {
        $query = HouseIcal::query()
            ->when($this->filters['created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['house_id'], fn ($query, $id) => $query->where('house_id', '=', $id))
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
    }

    public function housesResult()
    {
        if (!empty($this->houseSearch)) {
            $query = HouseTitle::query()->select(DB::raw("house_id as id, name AS title, slug as subtitle"))
                ->when($this->houseSearch, fn ($query, $name) => $query
                    ->where('name', 'like', '%' . $name . '%')
                    ->orWhere('house_id', 'like', '%' . $name . '%'))->limit(5);
            $this->houses = $query->get();
        }
    }
    public function setAutoCompleteItem($type, $text, $id)
    {
        $this->houseSearch = $text;
        $this->editing->house_id = $id;
        $this->houses = null;
    }

    public function exportSelected()
    {
        if ($this->selectedRowsQuery->count()) {
            return response()->streamDownload(function () {
                echo $this->selectedRowsQuery->toCsv();
            }, 'reservations.csv');
        }
    }

    public function render()
    {
        return view('livewire.admin.house.icals', [
            'icals' => $this->rows,
        ])->layout('layouts.admin');
    }
}
