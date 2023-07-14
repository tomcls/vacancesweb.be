<?php

namespace App\Http\Livewire\Admin\House;

use Exception;
use App\Models\User;
use Livewire\Component;
use App\Models\HousePackage;
use App\Models\HousePackageUser;
use App\Models\HousePackageLink;
use Illuminate\Support\Facades\DB;
use App\Traits\DataTable\WithSorting;
use App\Traits\DataTable\WithCachedRows;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithPerPagePagination;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class PackageUsers extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public HousePackageUser $editing;
    public HousePackageLink $assign;

    public $showAssignModal = false;
    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $users = null;
    public $userSearch = null;

    public $housePackages = [];

    public $filters = [
        'user_id' => null,
        'id' => null,
        'house_package_id' => null,
    ];

    public $rules = [
        "editing.id" => 'sometimes',
        "editing.house_package_id" => 'required',
        "editing.user_id" => 'required',
        "assign.email" => 'required',
        "assign.house_package_id" => 'required'
    ];
    protected $listeners = [
        'selectAutoCompleteItem' => 'setAutoCompleteItem'
    ];

    public function mount()
    {
        $this->editing = $this->makeBlankPackageUser();
    }
    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankPackageUser();

        $this->showEditModal = true;
    }

    public function edit(HousePackageUser $housePackage)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($housePackage)) {
            $this->editing = $housePackage;
        }

        $this->showEditModal = true;
    }

    public function openAssignModal(HousePackage $housePackage)
    {
        $this->assign = $this->makeBlankAssign();
        $this->housePackages = HousePackage::get();
        $this->showAssignModal = true;
    }

    public function assign()
    {
        $validator = FacadesValidator::make(['email' => $this->userSearch], []);
        $validator->sometimes('email', 'email', function ($input) {
            return true;
        });
        try {
            $valid = $validator->validate();
            $this->assign->email = $valid['email'];
            if ($this->assign->email && $this->assign->house_package_id) {
                $u = User::whereEmail($this->assign->email)->first();
                if ($u) {
                    $this->assign->user_id  = $u->id;
                }
                $this->assign->save();
                $this->showAssignModal = false;
                $this->notify(['message' => 'Package well assigned', 'type' => 'success']);
            } else {
                $this->notify(['message' => 'Please provide an email and a package', 'type' => 'alert']);
            }
        } catch (Exception $e) {
            $this->notify(['message' => 'Please provide a valid email and a package', 'type' => 'alert']);
        }
    }

    public function save()
    {
        if ($this->editing->house_package_id && $this->editing->user_id) {
            $this->editing->save();
            $this->showEditModal = false;
            $this->notify(['message' => 'User Package well assigned', 'type' => 'success']);
        } else {
            $this->notify(['message' => 'Please add a user id and a package id', 'type' => 'alert']);
        }
    }


    public function makeBlankPackageUser()
    {
        return HousePackageUser::make([]);
    }

    public function makeBlankAssign()
    {
        return HousePackageLink::make([]);
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function toggleShowFilters()
    {
        $this->showFilters = !$this->showFilters;
    }
    public function getRowsQueryProperty()
    {
        $query = HousePackageUser::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id))
            ->when($this->filters['user_id'], fn ($query, $user_id) => $query->where('user_id', '=', $user_id))
            ->when($this->filters['house_package_id'], fn ($query, $house_package_id) => $query->where('house_package_id', '=', $house_package_id));
        return $this->applySorting($query);
    }
    public function getRowsProperty()
    {
        return $this->applyPagination($this->rowsQuery);
    }

    public function usersResult()
    {
        if (!empty($this->userSearch)) {
            $query = User::query()->select(DB::raw(" id, CONCAT(id,'# ',firstname,' ', lastname) AS title, email as subtitle"))
                ->when($this->userSearch, fn ($query, $name) => $query
                    ->where('firstname', 'like', '%' . $name . '%')
                    ->orWhere('lastname', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%'))->limit(5);
            $this->users = $query->get();
        }
    }
    public function setAutoCompleteItem($type, $text, $id, $subtitle)
    {
        $this->userSearch = $subtitle;
        $this->assign->email = $subtitle;
        $this->users = null;
    }

    public function render()
    {
        return view('livewire.admin.house.package-users', [
            'packages' => $this->rows,
        ])->layout('layouts.admin');
    }
}
