<?php

namespace App\Http\Livewire\Admin\House;

use App\Models\House;
use App\Models\User;
use Livewire\Component;
use App\Models\HouseIcal;
use Illuminate\Support\Carbon;
use App\Models\HouseReservation;
use Illuminate\Support\Facades\DB;
use App\Repositories\IcalRepository;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class HouseReservations extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public HouseReservation $editing;

    public $houseId;

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
        "editing.enddate_for_editing" => 'required'
    ];

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showIcalModal = false;
    public $showFilters = false;

    public $houseReservations = [];

    public $selectedType = null;

    public $createInvoice = false;

    public $invoiceId = null;

    public HouseIcal $ical;

    public $icalUrl = null;

    public function mount($houseId)
    {
        $this->houseId = $houseId;
        $this->editing = $this->makeBlankReservation();
        $this->ical = $this->makeBlankIcal();

        $ical = HouseIcal::whereHouseId($this->houseId)->first();
        if ($ical) {
            $this->ical = $ical;
        }
        $this->houseReservations = HouseReservation::whereHouseId($this->houseId)->orderBy('id', 'desc')->limit(30)->get();
    }
    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' seasons', 'type' => 'success']);
    }
    public function setIcal()
    {
        $validatedData = $this->validate([
            'icalUrl' => 'required',
        ]);
        $iCal = new IcalRepository($this->icalUrl);
        $events = $iCal->eventsByDate();
        if (count($events)) {
            if (!$this->ical->id) {
                $this->ical->url = $this->icalUrl;
                $this->ical->house_id = $this->houseId;
                $this->ical->hash = md5(microtime());
                $this->ical->save();
            } else {
                $this->ical->update([
                    'url' => $this->icalUrl,
                    'house_id' => $this->houseId,
                    'hash' =>  md5(microtime())
                ]);
            }
            HouseReservation::whereHouseId($this->ical->house_id)->delete();
            $user_id = House::whereId($this->houseId)->first()->user->id;
            if (!empty($events) && count($events) > 0) {
                foreach ($events as $date => $events) {
                    foreach ($events as $event) {
                        if ($event->timeStart() != null && $event->timeEnd()  != 0) {
                            $start_date = date('Y-m-d H:i:s', $event->timeStart());
                            $end_date = date('Y-m-d H:i:s', $event->timeEnd());
                            $resa = new HouseReservation();
                            $resa->house_id = $this->ical->house_id;
                            $resa->user_id = $user_id;
                            $resa->startdate =  $start_date;
                            $resa->enddate = $end_date;
                            $resa->save();
                        }
                    }
                }
                $this->notify(['message' => 'Ical well set and well sync', 'type' => 'success']);
            } else {
                $this->notify(['message' => 'No events retrieved', 'type' => 'alert']);
            }
        } else {
            $this->notify(['message' => 'No event retrieved', 'type' => 'alert']);
        }

        $this->showIcalModal = false;
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
    public function openIcalModal(HouseReservation $houseReservation)
    {
        if ($this->ical->id) {
            $this->icalUrl = $this->ical->url;
        } else {
            $this->icalUrl = null;
        }
        $this->showIcalModal = true;
    }


    public function save()
    {
        // if no invoice_id then create an invoice else just create a new transaction
        $this->editing->house_id = $this->houseId;
        $v = $this->validate([
            "editing.user_id" => 'required',
            "editing.house_id" => 'required',
            "editing.startdate_for_editing" => 'required',
            "editing.enddate_for_editing" => 'required'
        ]);
        $this->editing->save();

        $this->showEditModal = false;

        $this->notify(['message' => 'Reservation  well saved', 'type' => 'success']);
    }

    public function makeBlankReservation()
    {
        return HouseReservation::make([]);
    }
    public function makeBlankIcal()
    {
        return HouseIcal::make([]);
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
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id))
            ->whereHouseId($this->houseId);

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
        return view('livewire.admin.house.house-reservations', [
            'reservations' => $this->rows,
        ]);
    }
}
