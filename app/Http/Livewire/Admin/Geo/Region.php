<?php

namespace App\Http\Livewire\Admin\Geo;

use App\Models\Country;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\Autocomplete\WithHeremap;
use App\Models\Region as ModelsRegion;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\App;
use App\Models\RegionDescription;
use App\Models\RegionTranslation;
use App\Models\HolidayType;
use App\Repositories\HereMapRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Region extends Component
{
    use WithHeremap;

    public ModelsRegion $region;

    public $lang = null;
    public $active = false;
    public $regions = null;
    public $countries = null;
    public $geometry = null;
    public $regionSearch = null;
    public $countrySearch = null;

    public $names = [];
    public $descriptions = [];
    public $types = [];
    public $type = 'default';

    public $levels = ['state', 'county', 'city'];

    protected $rules = [
        'region.custom' => 'sometimes',
        'region.level' => 'required',
        'region.country_id' => 'required',
        'region.longitude' => 'required',
        'region.latitude' => 'required',
        'region.sw_lat' => 'required',
        'region.sw_lon' => 'required',
        'region.ne_lat' => 'required',
        'region.ne_lon' => 'required',
    ];

    protected $listeners = [
        'selectAutoCompleteItem' => 'setAutoCompleteItem',
        'onMarkerDragend' => 'setLocation',
        'setTitle' => 'setTitle',
        'setPolygon' => 'setGeometry'
    ];
    public function mount(Request $request)
    {
        $this->lang =  App::currentLocale();
        array_push($this->types, 'default');
        HolidayType::all()->map(function ($type) {
            array_push($this->types, $type->code);
        });

        collect(config('app.langs'))->map(function ($lang) {
            $this->names[$lang] = $this->makeBlankRegionName($lang);
            $this->descriptions[$lang]['default'] = new RegionDescription(['lang' => $lang, 'type' => 'default']);

            collect($this->types)->map(function ($type) use ($lang) {

                $this->descriptions[$lang][$type] = new RegionDescription(['lang' => $lang, 'type' => $type]);
            });
        });
        try {
            $this->region = ModelsRegion::select('*')->addSelect(DB::raw(' ST_AsGeoJSON(geometry) geom'))->findOrFail($request['id']);

            $this->active = $this->region->active;
            $this->region->translations->map(function ($title) {
                $this->names[$title->lang] = $title;
            });
            $this->region->descriptions->map(function ($description) {
                $this->descriptions[$description->lang][$description->type] = $description;
            });
        } catch (ModelNotFoundException $e) {
            $this->region = $this->makeBlankRegion();
        }
    }

    public function makeBlankRegion()
    {
        return ModelsRegion::make([]);
    }
    public function active()
    {
        if ($this->active) {
            $this->active = false;
        } else {
            $this->active = true;
        }
    }
    public function makeBlankRegionName($lang)
    {
        return new RegionTranslation(['lang' => $lang, 'name' => '', 'slug' => '']);
    }

    public function save()
    {
        $this->emit("isNameValid");
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {

                if ($this->titleIsInvalid()) {
                    $validator->errors()->add('regionName.name', 'The title should be filled in');
                    $this->notify(['message'=>'The title should be filled in','type'=>'alert']);
                }
                if ($this->slugIsInvalid()) {
                    $validator->errors()->add('regionName.slug', 'The slug should be filled in');
                    $this->notify(['message'=>'The slug should be filled in','type'=>'alert']);
                }
            });
        })->validate();
        if (!$this->region->id) {
            $this->region->active = true;
        }
        if ($this->geometry) {
            $this->region->geometry = DB::raw("ST_GEOMFROMTEXT('" . $this->geometry . "')");
        }
        $this->region->save();
        $this->emit("saveContent", $this->region->id);
        $this->notify(['message'=>'Region well saved','type'=>'success']);
    }
    public function setGeometry($geometry)
    {
        $this->geometry = $this->toWKT($geometry);
    }
    public function titleIsInvalid()
    {
        foreach ($this->names as $title) {
            if ($title['name']) return false;
        }
        return true;
    }

    public function slugIsInvalid()
    {
        foreach ($this->names as $slug) {
            if (isset($slug['slug'])) return false;
        }
        return true;
    }

    public function countriesResult()
    {
        if (!empty($this->countrySearch)) {
            $query = Country::query()
            ->select(DB::raw("countries.id, name as title, concat(abbreviation,' ', iso_code) as subtitle"))
            ->leftJoin('country_translations', 'countries.id', '=', 'country_translations.country_id')
            ->when($this->countrySearch, fn ($query, $name) =>
                $query->where('name', 'like', '%' . $name . '%'))
            ->limit(5);
            $this->countries = $query->get();
        }
    }
    public function regionsResult()
    {
        if ($this->regionSearch) {
            $heremap = new HereMapRepository();

            $suggestions = $heremap->suggest([
                'query' => urlencode($this->regionSearch),
                'language' => $this->lang,
            ]);
            $this->regions = $this->formatSuggestions($suggestions, ['state', 'county', 'city']);
        }
    }

    public function setAutoCompleteItem($type, $text, $id)
    {
        switch ($type) {
            case 'location':
                $heremap = new HereMapRepository();
                $position = $heremap->geocode([
                    'searchtext' => $id,
                    'language' => $this->lang,
                ]);
                if(!$this->region->level) {
                    $this->notify(['message'=>'Please set a level','type'=>'alert']);
                    return;
                }
                if (isset($position->location->displayPosition)) {

                    $this->regionSearch = $text;

                    $this->region->longitude = $position->location->displayPosition->longitude;
                    $this->region->latitude = $position->location->displayPosition->latitude;

                    $region = new ModelsRegion();
                    $digResult = $region->dig($this->region->longitude, $this->region->latitude, false);
                    if ($digResult && !empty($digResult['region'][$this->region->level])) {
                        $regionGeometry = $digResult['region'][$this->region->level]['geom'];

                        $this->region->country_id =  $regionGeometry->country_id;
                        $this->region->sw_lat = $regionGeometry->sw_lat;
                        $this->region->sw_lon = $regionGeometry->sw_lon;
                        $this->region->ne_lat = $regionGeometry->ne_lat;
                        $this->region->ne_lon = $regionGeometry->ne_lon;

                        $this->region->longitude = $position->location->displayPosition->longitude;
                        $this->region->latitude = $position->location->displayPosition->latitude;

                        $this->geometry = $regionGeometry->geometry;
                        $this->region->geom = $this->toJSON($this->geometry);
                        foreach (config('app.langs') as $lang) {
                            $this->names[$lang] = $digResult['region'][$this->region->level]['names'][$lang];
                            $this->emit('nameChanged'.$lang, $this->names[$lang]);
                        }
                        $this->countrySearch = $this->region->country->translation($this->lang)->first()->name;
                    }
                    $this->emit('locationChanged', [
                        "lat"  => $position->location->displayPosition->latitude,
                        "lng"  => $position->location->displayPosition->longitude,
                        "geom" => $this->region->geom,
                        "ne_lat" => $this->region->ne_lat,
                        "ne_lon" => $this->region->ne_lon,
                        "sw_lat" => $this->region->sw_lat,
                        "sw_lon" => $this->region->sw_lon
                    ]);
                }
                break;
            case 'region.user_id':
                $this->regionSearch = $text;
                $this->region->user_id = $id;
                break;
            default:
                # code...
                break;
        }
    }

    public function setLocation($location)
    {
        $this->region->longitude = $location['lng'];
        $this->region->latitude = $location['lat'];
    }

    public function refreshContent()
    {
        $this->emit('refreshContent', $this->lang, $this->type);
    }

    public function render()
    {
        return view('livewire.admin.geo.region')->layout('layouts.admin');
    }
}
