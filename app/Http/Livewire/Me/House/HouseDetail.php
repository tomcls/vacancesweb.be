<?php

namespace App\Http\Livewire\Me\House;

use App\Models\User;
use App\Models\Region;
use Livewire\Component;
use App\Models\HouseType;
use App\Models\HouseTitle;
use App\Models\HouseRegion;
use App\Models\HouseDescription;
use App\Models\House as HouseModel;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Validator;
use App\Repositories\HereMapRepository;
use App\Traits\Autocomplete\WithHeremap;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class HouseDetail extends Component
{
    use WithHeremap;
    public User $user;
    public HouseModel $house;

    public $lang = null;
    public $users = null;
    public $active = false;
    public $regions = null;
    public $userSearch = null;
    public $regionSearch = null;
    public $houseTypes = null;

    public $titles = [];
    public $descriptions = [];

    protected $rules = [
        'house.user_id' => 'required',
        'house.street' => 'required',
        'house.street_number' => 'sometimes',
        'house.street_box' => 'sometimes',
        'house.zip' => 'sometimes',
        'house.city' => 'sometimes',
        'house.longitude' => 'required',
        'house.latitude' => 'required',
        'house.house_type_id' => 'required',
        'house.email' => 'required',
        'house.phone' => 'required',
        'house.rooms' => 'sometimes',
        'house.acreage' => 'sometimes',
        'house.number_people' => 'sometimes',
    ];

    protected $listeners = [
        'selectAutoCompleteItem' => 'setAutoCompleteItem',
        'onMarkerDragend' => 'setLocation',
        'setTitle' => 'setTitle'
    ];
    public function mount($houseId)
    {
        $this->user = auth()->user();
        $this->houseTypes = HouseType::all();

        $this->lang =  App::currentLocale();

        collect(config('app.langs'))->map(function ($lang) {
            $this->titles[$lang] = $this->makeBlankHouseTitle($lang);
            $this->descriptions[$lang] = $this->makeBlankHouseDescription($lang);
        });

        try {
            $this->house = HouseModel::findOrFail($houseId);
            $this->active = $this->house->active;
            $this->house->houseTitles->map(function ($title) {
                $this->titles[$title->lang] = $title;
            });
            $this->house->houseDescriptions->map(function ($description) {
                $this->descriptions[$description->lang] = $description;
            });
            $this->userSearch = $this->house->user->firstname . "  " . $this->house->user->lastname;
            
        } catch (ModelNotFoundException $e) {
            $this->house = $this->makeBlankHouse();
            $this->house->user_id = $this->user->id;
        }
    }

    public function makeBlankHouse()
    {
        return HouseModel::make([]);
    }
    public function active()
    {
        if ($this->active) {
            $this->active = false;
        } else {
            $this->active = true;
        }
    }
    public function makeBlankHouseTitle($lang)
    {
        return new HouseTitle(['lang' => $lang, 'name' => '', 'slug' => '']);
    }

    public function makeBlankHouseDescription($lang)
    {
        return HouseDescription::make(['lang' => $lang]);
    }

    public function save()
    {
        $this->emit("isTitleValid");
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {

                if ($this->titleIsInvalid()) {
                    $validator->errors()->add('houseTitle.name', 'The title should be filled in');
                    $this->notify(['message'=>'The title should be filled in','type'=>'alert']);
                }
                if ($this->slugIsInvalid()) {
                    $validator->errors()->add('slug', 'The slug should be filled in');
                    $this->notify(['message'=>'The slug should be filled in','type'=>'alert']);
                }
            });
        })->validate();
        $this->house->active = $this->active;
        $this->house->save();
        $this->emit("saveContent", $this->house->id);

        $digResult = Region::dig($this->house->longitude, $this->house->latitude);
        if(!empty($digResult['region'])) {
            $houseRegions = [];
            foreach ($digResult['region'] as $level) {
               array_push($houseRegions,['region_id'=>$level['geom']->id,'house_id'=>$this->house->id]);
            }
            HouseRegion::whereHouseId($this->house->id)->delete();
            HouseRegion::insertOrIgnore($houseRegions);
        }
        $this->emit("setHouseId", $this->house->id);
        $this->notify(['message'=>'House well saved','type'=>'success']);
    }

    public function titleIsInvalid()
    {
        foreach ($this->titles as $title) {
            if ($title['name']) return false;
        }
        return true;
    }

    public function slugIsInvalid()
    {
        foreach ($this->titles as $slug) {
            if (isset($slug['slug'])) return false;
        }
        return true;
    }

    public function regionsResult()
    {
        if ($this->regionSearch) {
            $heremap = new HereMapRepository();

            $suggestions = $heremap->suggest([
                'query' => urlencode($this->regionSearch),
                'language' => $this->lang,
            ]);
            $this->regions = $this->formatSuggestions($suggestions);
        }
    }

    public function setAutoCompleteItem($type, $text, $id)
    {
        $heremap = new HereMapRepository();
        $position = $heremap->geocode([
            'searchtext' => $id,
            'language' => $this->lang,
        ]);
        if (isset($position->location->displayPosition)) {

            $this->regionSearch = $text;
            $this->house->longitude = $position->location->navigationPosition[0]['Longitude'];
            $this->house->latitude = $position->location->navigationPosition[0]['Latitude'];
            $this->house->street = $position->location->address->street??null;
            $this->house->street_number = $position->location->address->houseNumber??null;
            $this->house->city = $position->location->address->city??null;
            $this->house->zip = $position->location->address->postalCode??null;

            $this->regions = null;

            $this->emit('locationChanged', ["lat" => $position->location->displayPosition->latitude, "lng" => $position->location->displayPosition->longitude]);
        }
    }

    public function setLocation($location)
    {
        $this->house->longitude = $location['lng'];
        $this->house->latitude = $location['lat'];
    }

    public function refreshContent()
    {
        $this->emit('refreshContent', $this->lang);
    }

    public function setTitle($lang, $title)
    {
        $this->titles[$lang]['name'] = $title;
        $this->titles[$lang]['slug'] = $title;
        $this->emit('refreshContent', $this->lang);
    }

    public function render()
    {
        return view('livewire.me.house.house-detail');
    }
}
