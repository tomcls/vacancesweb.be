<?php

namespace App\Http\Livewire\Admin\User;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Users extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;
    public $password = '';
    public $passwordConfirmation = '';
    public $changePassword = false;

    public $filters = [
        'id' => null,
        'search' => null,
        'firstname' => null,
        'lastname' => null,
        'phone' => null,
        'lang' => null,
        'email' => null,
        'code' => null,
        'date-created-min' => null,
        'date-created-max' => null,
    ];
    public User $editing;

    protected $queryString = ['sorts'];

    protected $listeners = ['refreshUsers' => '$refresh'];

    public function rules()
    {
        return [
            'editing.firstname' => 'required|min:2',
            'editing.lastname' => 'required',
            'editing.email' => 'required|email',
            'editing.phone' => 'sometimes',
            'editing.active' => 'sometimes',
            'editing.code' => 'sometimes',
            'editing.lang' => 'required',
            'password' => 'required|min:6|same:passwordConfirmation'

            // 'editing.status' => 'required|in:'.collect(User::STATUSES)->keys()->implode(','),
            //'editing.date_for_editing' => 'required',
        ];
    }
    public function mount()
    {
        $this->editing = $this->makeBlankUser();
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
            }, 'users.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' users','type'=>'success']);
    }

    public function makeBlankUser()
    {
        return User::make([]);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankUser();

        $this->showEditModal = true;
    }

    public function edit(User $user)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($user)) $this->editing = $user;

        $this->showEditModal = true;
    }

    public function save()
    {
        
        $this->validate();

        if(!$this->editing->id) {
            $this->editing->password = Hash::make($this->password);
        }
        if($this->editing->id && $this->changePassword) {
            $this->editing->password = Hash::make($this->password);
            $this->notify(['message'=>'Password well updated','type'=>'success']);
        }
        $this->editing->save();
        
        $this->showEditModal = false;

        $this->notify(['message'=>'User well saved','type'=>'success']);

    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = User::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', $id))
            ->when($this->filters['date-created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['date-created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['code'], fn ($query, $code) => $query->where('code', '>=', $code))
            ->when($this->filters['phone'], fn ($query, $phone) => $query->where('phone', '>=', $phone))
            ->when($this->filters['email'], fn ($query, $name) => $query->where('email',  'like', '%' . $name . '%'))
            ->when($this->filters['firstname'], fn ($query, $name) => $query->where('firstname',  'like', '%' . $name . '%'))
            ->when($this->filters['lastname'], fn ($query, $name) => $query->where('lastname',  'like', '%' . $name . '%'))
            ->when($this->filters['search'], fn ($query, $search) =>
            $query->where('id', '=', $search)
                ->orWhere('firstname', 'like', '%' . $search . '%')
                ->orWhere('lastname', 'like', '%' . $search . '%')
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
        return view('livewire.admin.user.users', [
            'users' => $this->rows,
        ])->layout('layouts.admin');
    }
}
