<?php

namespace App\Http\Livewire\Admin\House;

use App\Models\HouseContactBanned;
use Livewire\Component;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;
use Carbon\Carbon;

class ContactsBanned extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $filters = [
        'id' => null,
        'search' => null,
        'email' => null,
        'date-created-min' => null,
        'date-created-max' => null,
    ];
    public HouseContactBanned $editing;

    protected $queryString = ['sorts'];

    protected $listeners = ['refreshContactsBanneds' => '$refresh'];

    public function rules()
    {
        return [
            'editing.email' => 'required|email',
        ];
    }
    public function mount()
    {
        $this->editing = $this->makeBlankContactsBanned();
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
            }, 'banned-contacts.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' contacts','type'=>'success']);
    }

    public function makeBlankContactsBanned()
    {
        return HouseContactBanned::make([]);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankHouseContactBanned();

        $this->showEditModal = true;
    }

    public function edit(HouseContactBanned $user)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($user)) $this->editing = $user;

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        $this->editing->save();
        
        $this->showEditModal = false;

        $this->notify(['message'=>'Banned contact well saved','type'=>'success']);
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = HouseContactBanned::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', $id))
            ->when($this->filters['date-created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['date-created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['email'], fn ($query, $name) => $query->where('email',  'like', '%' . $name . '%'))
            ->when($this->filters['search'], fn ($query, $search) =>
            $query->where('id', '=', $search)
                ->orWhere('email', 'like', '%' . $search . '%'));

        return $this->applySorting($query);
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
        return view('livewire.admin.house.contacts-banned', [
            'contacts' => $this->rows,
        ])->layout('layouts.admin');
    }
}
