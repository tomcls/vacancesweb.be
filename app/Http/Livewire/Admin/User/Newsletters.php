<?php

namespace App\Http\Livewire\Admin\User;

use App\Models\Newsletter;
use Livewire\Component;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;
use App\Models\User;
use Carbon\Carbon;

class Newsletters extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $filters = [
        'id' => null,
        'search' => null,
        'lang' => null,
        'email' => null,
        'unsubscribe' => null,
        'date-created-min' => null,
        'date-created-max' => null,
    ];
    public Newsletter $editing;

    protected $queryString = ['sorts'];

    protected $listeners = ['refreshNewsletters' => '$refresh'];

    public function rules()
    {
        return [
            'editing.email' => 'required|email',
            'editing.unsubscribe' => 'sometimes',
            'editing.lang' => 'sometimes',
        ];
    }
    public function mount()
    {
        $this->editing = $this->makeBlankNewsletter();
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
            }, 'newsletters.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' newsletters','type'=>'success']);
    }

    public function makeBlankNewsletter()
    {
        return Newsletter::make([]);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankNewsletter();

        $this->showEditModal = true;
    }

    public function edit(Newsletter $user)
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

        $this->notify(['message'=>'Newsletter well saved','type'=>'success']);
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = Newsletter::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', $id))
            ->when($this->filters['date-created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['date-created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['email'], fn ($query, $name) => $query->where('email',  'like', '%' . $name . '%'))
            ->when($this->filters['lang'], fn ($query, $lang) => $query->where('lang',  '=', $lang ))
            ->when($this->filters['unsubscribe'], fn ($query, $unsubscribe) => $query->where('unsubscribe',  '=', $unsubscribe))
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
        return view('livewire.admin.user.newsletters', [
            'newsletters' => $this->rows,
        ])->layout('layouts.admin');
    }
}
