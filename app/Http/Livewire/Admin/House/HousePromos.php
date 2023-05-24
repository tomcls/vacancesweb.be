<?php

namespace App\Http\Livewire\Admin\House;

use Livewire\Component;
use App\Traits\DataTable\WithPerPagePagination;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\DataTable\WithBulkActions;
use App\Traits\DataTable\WithCachedRows;
use App\Models\HousePromoTranslation;
use App\Traits\DataTable\WithSorting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\HousePromo;
use App\Models\HousePromoLink;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class HousePromos extends Component
{
    use WithPerPagePagination, WithSorting, WithBulkActions, WithCachedRows;

    public HousePromo $editing;
    public HousePromoLink $assign;
    
    public $lang = null;
    protected $queryString = ['sorts'];

    public $titles = [];

    public $filters = [
        'lang' => null,
        'id' => null,
        'code' => null,
        'name' => null,
        'price' => null,
    ];

    public $rules = [
        "editing.id" => 'sometimes',
        "editing.code" => 'required',
        "editing.price" => 'required',
        "titles.*.name" => 'sometimes',
        "assign.email" => 'required',
        "assign.house_promo_id" => 'required'
    ];
    protected $listeners = [
        'setPromoName' => 'setTitle',
        'refreshComponent' => '$refresh',
        'selectAutoCompleteItem' => 'setAutoCompleteItem'
    ];
    public $showAssignModal = false;
    public $showDeleteModal = false;
    public $showEditModal = false;
    public $showFilters = false;

    public $users = null;
    public $userSearch = null;


    public $housePromos = [];

    public function mount()
    {

        $this->editing = $this->makeBlankPromo();

        $this->lang = $this->filters['lang'] = App::currentLocale();

        collect(config('app.langs'))->map(function ($lang) {
            $this->titles[$lang] = $this->makeBlankPromoTranslation($lang);
        });
        logger($this->titles);
    }

    public function deleteSelected()
    {
        $deleteCount = $this->selectedRowsQuery->count();

        $this->selectedRowsQuery->delete();

        $this->showDeleteModal = false;

        $this->notify(['message' => 'You\'ve deleted ' . $deleteCount . ' promo(s)', 'type' => 'success']);
    }

    public function create()
    {
        $this->useCachedRows();

        if ($this->editing->getKey()) $this->editing = $this->makeBlankPromo();

        $this->showEditModal = true;
    }

    public function edit(HousePromo $housePromo)
    {
        $this->useCachedRows();

        if ($this->editing->isNot($housePromo)) {
            $this->editing = $housePromo;
            foreach ($this->titles as $lang => $title) {
                $this->titles[$lang] = HousePromoTranslation::whereHousePromoId($housePromo->id)->first();
            }
        }

        $this->showEditModal = true;
    }
    public function openAssignModal(HousePromo $housePromo)
    {
        $this->assign = $this->makeBlankAssign();
        $this->housePromos = HousePromo::get();
        $this->showAssignModal = true;
    }
    public function assign()
    {
        $validator = FacadesValidator::make(['email'=>$this->userSearch], []);
        $validator->sometimes('email', 'email', function ($input) {
            return true;
          });
        try {
            $valid = $validator->validate();
            $this->assign->email = $valid['email'];
            if($this->assign->email && $this->assign->house_promo_id) {
                $u = User::whereEmail($this->assign->email)->first();
                if($u) {
                    $this->assign->user_id  = $u->id;
                }
                $this->assign->save();
                $this->showAssignModal = false;
                $this->notify(['message' => 'Promo weel assigned', 'type' => 'success']);
            } else {
                $this->notify(['message' => 'Please provide an email and a promo', 'type' => 'alert']);
            }
        } catch (Exception $e) {
            $this->notify(['message' => 'Please provide a valid email and a promo', 'type' => 'alert']);
        }
    }

    public function save()
    {
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {

                if ($this->promoNameIsInvalid()) {
                    $validator->errors()->add('title.name', 'The name should be filled in');
                    $this->notify(['message' => 'The name should be filled in', 'type' => 'alert']);
                }
            });
        })->validate();

        if ($this->editing->id) {
            $this->editing->update();
        } else {
            $this->editing->save();
        }
        foreach ($this->titles as $lang => $title) {
            $housePromoTranslation = HousePromoTranslation::whereHousePromoId($this->editing->id)->whereLang($lang)->first();
            if ($housePromoTranslation) {
                $translation = new HousePromoTranslation([
                    'id' => $housePromoTranslation->id,
                    'house_promo_id' => $this->editing->id,
                    'lang' => $lang,
                    'name' => $title['name']
                ]);
                $translation->update();
            } else {
                $translation = new HousePromoTranslation([
                    'house_promo_id' => $this->editing->id,
                    'lang' => $lang,
                    'name' => $title['name']
                ]);
                $translation->save();
            }
        }
        // $this->emit("savepromo",$this->editing->id);



        $this->showEditModal = false;

        $this->notify(['message' => 'Promo well saved', 'type' => 'success']);
    }

    public function makeBlankPromoTranslation($lang)
    {
        return new HousePromoTranslation(['lang' => $lang, 'name' => '']);
    }

    public function makeBlankPromo()
    {
        return HousePromo::make([]);
    }
    public function makeBlankAssign()
    {
        return HousePromoLink::make([]);
    }

    public function promoNameIsInvalid()
    {
        foreach ($this->titles as $title) {
            if ($title['name']) return false;
        }
        return true;
    }

    public function resetFilters()
    {
        $this->reset('filters');
    }

    public function toggleShowFilters()
    {
        //$this->useCachedRows();

        $this->showFilters = !$this->showFilters;
    }

    public function getRowsQueryProperty()
    {
        $query = HousePromoTranslation::query()
            ->when($this->filters['id'], fn ($query, $id) => $query->where('id', '=', $id))
            ->when($this->filters['price'], fn ($query, $price) => $query->where('price', '=', $price))
            ->when($this->filters['name'], fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'))
            ->when($this->filters['code'], fn ($query, $name) =>
            $query->whereHas('promo', fn ($query) => $query->where('code', 'like', '%' . $name . '%')))
            ->whereLang($this->lang);
        return $this->applySorting($query);
    }

    public function setTitle($lang, $title)
    {
        $this->titles[$lang]['name'] = $title;
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
            $query = User::query()->select(DB::raw(" id, CONCAT(id,'# ',firstname,' ', lastname) AS title, email as subtitle"))
                ->when($this->userSearch, fn ($query, $name) => $query
                    ->where('firstname', 'like', '%' . $name . '%')
                    ->orWhere('lastname', 'like', '%' . $name . '%')
                    ->orWhere('email', 'like', '%' . $name . '%'))->limit(5);
            $this->users = $query->get();
            logger($this->users);
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
        return view('livewire.admin.house.house-promos', [
            'promos' => $this->rows,
        ])->layout('layouts.admin');
    }
}
