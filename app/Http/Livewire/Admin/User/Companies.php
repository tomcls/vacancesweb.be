<?php

namespace App\Http\Livewire\Admin\User;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;

class Companies extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $filters = [
        'id' => null,
        'search' => null,
        'name' => null,
        'vat' => null,
        'street' => null,
        'zip' => null,
        'country' => null,
        'email' => null,
        'phone' => null,
        'email' => null,
        'active' => null,
        'date-created-min' => null,
        'date-created-max' => null,
    ];
    public Company $editing;

    protected $queryString = ['sorts'];

    protected $listeners = ['refreshCompanies' => '$refresh','selectAutoCompleteItem' => 'setAutoCompleteItem'];

    public $users = null;
    public $userSearch = null;

    public function rules()
    {
        return [
            'editing.name' => 'required|min:2|max:150',
            'editing.vat' => 'required|max:150',
            'editing.street' => 'required|max:200',
            'editing.street_number' => 'sometimes|max:7',
            'editing.street_box' => 'sometimes|max:7',
            'editing.phone' => 'sometimes|max:40',
            'editing.email' => 'required|email|max:150',
            'editing.active' => 'sometimes',
            'editing.zip' => 'sometimes|max:10',
            'editing.country' => 'sometimes|max:100',
            'editing.active' => 'required',
            'editing.user_id' => 'required',
        ];
    }

    public function mount()
    {
        $this->editing = $this->makeBlankCompany();
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
            }, 'companies.csv');
        }
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message'=>'You\'ve deleted ' . $deleteCount . ' companies','type'=>'success']);
    }

    public function makeBlankCompany()
    {
        return Company::make([]);
    }

    public function toggleShowFilters()
    {
        $this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankCompany();
        $this->userSearch = null;
        $this->showEditModal = true;
    }

    public function edit(Company $company)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($company)){
            $this->editing = $company;
            $this->userSearch = $company->user->id."# ". $company->user->firstname. " ". $company->user->lastname;
        } 

        $this->showEditModal = true;
    }

    public function save()
    {
        $this->validate();

        $this->editing->save();
        
        $this->showEditModal = false;

        $this->notify(['message'=>'Company well saved','type'=>'success']);
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function getRowsQueryProperty()
    {
        $query = Company::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', $id))
            ->when($this->filters['date-created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($this->filters['date-created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($this->filters['vat'], fn ($query, $vat) => $query->where('vat', '=', $vat))
            ->when($this->filters['phone'], fn ($query, $phone) => $query->where('phone', '>=', $phone))
            ->when($this->filters['email'], fn ($query, $email) => $query->where('email',  'like', '%' . $email . '%'))
            ->when($this->filters['name'], fn ($query, $name) => $query->where('name',  'like', '%' . $name . '%'))
            ->when($this->filters['zip'], fn ($query, $zip) => $query->where('zip',  'like', '%' . $zip . '%'))
            ->when($this->filters['active'], fn ($query, $active) => $query->where('active',  '=',  $active ))
            ->when($this->filters['street'], fn ($query, $street) => $query->where('street', '%' . $street . '%' ))
            ->when($this->filters['search'], fn ($query, $search) =>
            $query->where('id', '=', $search)
                ->orWhere('name', 'like', '%' . $search . '%')
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
    // Fetch records
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
    public function setAutoCompleteItem($type,$text, $id)
    {
        switch ($type) {
            case 'editing.user_id':
                $this->userSearch = $text;
                $this->editing->user_id = $id;
                $this->users = null;
                break;

            default:
                # code...
                break;
        }
    }

    public function render()
    {
        return view('livewire.admin.user.companies', [
            'companies' => $this->rows,
        ])->layout('layouts.admin');
    }
}
