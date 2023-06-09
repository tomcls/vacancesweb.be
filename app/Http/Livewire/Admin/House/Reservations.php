<?php

namespace App\Http\Livewire\Admin\House;

use App\Models\House;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\HouseReservation;
use App\Repositories\IcalRepository;
use Illuminate\Support\Facades\DB;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Reservations extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public HouseReservation $editing;


    public $users = null;
    public $userSearch = null;

    protected $queryString = ['sorts'];

    public $filters = [
        'id' => null,
        'user_id' => null,
        'house_id' => null,
        'startdate-min' => null,
        'startdate-max' => null,
        'enddate-min' => null,
        'enddate-max' => null,
        'created-min' => null,
        'created-max' => null,
    ];
    protected $listeners = [
        'selectAutoCompleteItem' => 'setAutoCompleteItem',
    ];

    public $rules = [
        "editing.user_id" => 'required',
        "editing.house_id" => 'required',
        "editing.startdate_for_editing" => 'required',
        "editing.enddate_for_editing" => 'required',
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $houseReservations = [];

    public $selectedType = null;

    public $createInvoice = false;

    public $invoiceId = null;


    public function mount()
    {
        $this->editing = $this->makeBlankReservation();
    }

    public function makeBlankReservation()
    {
        return HouseReservation::make([]);
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

        if ($this->editing->getKey()) $this->editing = $this->makeBlankReservation();

        $this->showEditModal = true;
    }

    public function edit(HouseReservation $houseReservation)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($houseReservation)) {
            $this->editing = $houseReservation;
        }
        $this->showEditModal = true;
    }

    public function save()
    {
        // if no invoice_id then create an invoice else just create a new transaction
        $this->validate();
        $this->editing->save();

        $this->showEditModal = false;

        $this->notify(['message' => 'Reservation  well saved', 'type' => 'success']);
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
        $query = HouseReservation::query()
            ->select('id', 'house_id', 'user_id', 'startdate', 'enddate', 'created_at')
            ->when($this->filters['startdate-min'], fn ($query, $date) => $query->where('startdate', '>=', Carbon::parse($date)))
            ->when($this->filters['startdate-max'], fn ($query, $date) => $query->where('startdate', '<=', Carbon::parse($date)))
            ->when($this->filters['enddate-min'], fn ($query, $date) => $query->where('enddate', '>=', Carbon::parse($date)))
            ->when($this->filters['enddate-max'], fn ($query, $date) => $query->where('enddate', '<=', Carbon::parse($date)))
            ->when($this->filters['created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['house_id'], fn ($query, $id) => $query->where('house_id', '=', $id))
            ->when($this->filters['user_id'], fn ($query, $id) => $query->where('user_id', '=', $id))
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id));

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
        // return $this->cache(function () {
        //     return $this->applyPagination($this->rowsQuery);
        // });
    }

    public function usersResult()
    {
        if (!empty($this->userSearch)) {
            $query = User::query()->select(DB::raw("id, CONCAT(firstname,' ', lastname) AS title, email as subtitle"))
                ->when($this->userSearch, fn ($query, $name) => $query
                    ->where('firstname', 'like', '%' . $name . '%')
                    ->orWhere('lastname', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%'))->limit(5);
            $this->users = $query->get();
        }
    }
    public function setAutoCompleteItem($type, $text, $id)
    {
        $this->userSearch = $text;
        $this->editing->user_id = $id;
        $this->users = null;
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
        return view('livewire.admin.house.reservations', [
            'reservations' => $this->rows,
        ])->layout('layouts.admin');
    }
}
