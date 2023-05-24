<?php

namespace App\Http\Livewire\Admin\Geo;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Traits\Autocomplete\WithHeremap;
use App\Models\Country as ModelsCountry;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\App;
use App\Models\CountryDescription;
use App\Models\CountryTranslation;
use App\Models\HolidayType;
use App\Repositories\HereMapRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;

class Country extends Component
{
    use WithHeremap;

    public ModelsCountry $country;

    public $lang = null;
    public $active = false;
    public $countries = null;
    public $geometry = null;
    public $countrySearch = null;

    public $names = [];
    public $descriptions = [];
    public $types = [];
    public $type = 'default';

    protected $rules = [
        'country.abbreviation' => 'required',
        'country.iso_code' => 'required',
        'country.longitude' => 'required',
        'country.latitude' => 'required',
        'country.sw_lat' => 'required',
        'country.sw_lon' => 'required',
        'country.ne_lat' => 'required',
        'country.ne_lon' => 'required',
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
            $this->names[$lang] = $this->makeBlankCountryName($lang);
            $this->descriptions[$lang]['default'] = new CountryDescription(['lang'=>$lang,'type'=>'default']);
            
            collect($this->types)->map(function ($type) use ($lang) {
                
                $this->descriptions[$lang][$type] = new CountryDescription(['lang'=>$lang,'type'=>$type]);
            });
        });
        try {
            $this->country = ModelsCountry::select('*')->addSelect(DB::raw(' ST_AsGeoJSON(geometry) geom'))->findOrFail($request['id']);

            $this->active = $this->country->active;
            $this->country->translations->map(function ($title) {
                $this->names[$title->lang] = $title;
            });
            $this->country->descriptions->map(function ($description) {
                $this->descriptions[$description->lang][$description->type] = $description;
                // HolidayType::all()->map(function ($type) use ($description) {
                //     if($description->type == $type->code) {
                //         $this->descriptions[$description->lang][$type->code] = $description;
                //     }
                // });
            });
        } catch (ModelNotFoundException $e) {
            $this->country = $this->makeBlankCountry();
        }
    }

    public function makeBlankCountry()
    {
        return ModelsCountry::make([]);
    }
    public function active()
    {
        if ($this->active) {
            $this->active = false;
        } else {
            $this->active = true;
        }
    }
    public function makeBlankCountryName($lang)
    {
        return new CountryTranslation(['lang' => $lang, 'name' => '', 'slug' => '']);
    }

    // public function makeBlankCountryDescription($lang)
    // {
    //     return CountryDescription::make(['lang' => $lang]);
    // }

    public function save()
    {
        $this->emit("isNameValid");
        $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {

                if ($this->titleIsInvalid()) {
                    $validator->errors()->add('countryName.name', 'The title should be filled in');
                    $this->notify(['message'=>'The title should be filled in','type'=>'alert']);
                }
                if ($this->slugIsInvalid()) {
                    $validator->errors()->add('countryName.slug', 'The slug should be filled in');
                    $this->notify(['message'=>'The slug should be filled in','type'=>'alert']);
                }
            });
        })->validate();
        if(!$this->country->id) {
            $this->country->active = true;
        }
        if($this->geometry) {
            $this->country->geometry = DB::raw("ST_GEOMFROMTEXT('" . $this->geometry . "')");
        }
        $this->country->save();
        $this->emit("saveContent", $this->country->id);
        $this->notify(['message'=>'Country well saved','type'=>'success']);
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
        if ($this->countrySearch) {
            $heremap = new HereMapRepository();

            $suggestions = $heremap->suggest([
                'query' => urlencode($this->countrySearch),
                'language' => $this->lang,
            ]);
            $this->countries = $this->formatSuggestions($suggestions, ['country']);
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
                if (isset($position->location->displayPosition)) {

                    $this->countrySearch = $text;

                    $this->country->longitude = $position->location->displayPosition->longitude;
                    $this->country->latitude = $position->location->displayPosition->latitude;

                    $filters = [
                        'prox' => $position->location->displayPosition->latitude.",".$position->location->displayPosition->longitude.",250",
                        'mode' => 'retrieveAreas',
                        'maxResults' => 1,
                        'language' => 'en',
                        'additionaldata' => 'IncludeShapeLevel,Country'
                    ];

                    $hereCountry = $heremap->reverse($filters);
                    if($hereCountry) {
                        $countryGeometry = $heremap->simplify($hereCountry->location->shape['Value'], 'country');
                        $bbox = $countryGeometry->getBBox();
                        $this->country->iso_code = $hereCountry->location->address->country;
                        $this->country->abbreviation = substr($this->country->iso_code, 0, 2);
                        $this->country->sw_lat = $bbox["miny"];
                        $this->country->sw_lon = $bbox["minx"];
                        $this->country->ne_lat = $bbox["maxy"];
                        $this->country->ne_lon = $bbox["maxx"];
                        $this->geometry = $countryGeometry->out('wkt');//DB::raw("ST_GEOMFROMTEXT('" . $countryGeometry->out('wkt') . "')");
                        $this->country->geom = $countryGeometry->out('json');
                        $additionalData = $hereCountry->location->address->additionalData;
                        $name = null;
                        foreach ($additionalData as $d) {
                            if ($d['key'] == 'CountryName') {
                                $name = $d['value'];
                                break;
                            }
                        }
                        $this->names['en'] = new CountryTranslation(['lang' => 'en', 'name' => $name, 'slug' => Str::slug($name)]);
                        $this->emit('nameChangeden', $this->names['en']);
                        foreach (config('app.langs') as $lang) {
                            if ($lang != 'en') {
                                $filters = [
                                    'prox' => $position->location->displayPosition->latitude.",".$position->location->displayPosition->longitude.",250",
                                    'mode' => 'retrieveAreas',
                                    'maxResults' => 1,
                                    'language' => $lang
                                ];
                                $hereCountry = $heremap->reverse($filters);
                                $additionalData = $hereCountry->location->address->additionalData;
                                $name = null;
                                foreach ($additionalData as $d) {
                                    if ($d['key'] == 'CountryName') {
                                        $name = $d['value'];
                                        break;
                                    }
                                }
                                $this->names[$lang] = new CountryTranslation(['lang' => $lang, 'name' => $name, 'slug' => Str::slug($name)]);
                                $this->emit('nameChanged'.$lang, $this->names[$lang]);
                            }
                        }
                    }
                    $this->emit('locationChanged', [
                        "lat"  => $position->location->displayPosition->latitude, 
                        "lng"  => $position->location->displayPosition->longitude, 
                        "geom" => $this->country->geom, 
                        "ne_lat" => $this->country->ne_lat, 
                        "ne_lon" => $this->country->ne_lon, 
                        "sw_lat" => $this->country->sw_lat, 
                        "sw_lon" => $this->country->sw_lon]);
                }
                break;
            case 'country.user_id':
                $this->countrySearch = $text;
                $this->country->user_id = $id;
                break;
            default:
                # code...
                break;
        }
    }

    public function setLocation($location)
    {
        $this->country->longitude = $location['lng'];
        $this->country->latitude = $location['lat'];
    }

    public function refreshContent()
    {
        $this->emit('refreshContent', $this->lang, $this->type);
    }

    public function render()
    {
        return view('livewire.admin.geo.country')->layout('layouts.admin');
    }
}
