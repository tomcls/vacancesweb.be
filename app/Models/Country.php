<?php

namespace App\Models;

use App\Repositories\HereMapRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Country extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'iso_code',
        'abbreviation',
        'longitude',
        'latitude',
        'sw_lat',
        'sw_lon',
        'ne_lat',
        'ne_lon',
        'geometry',
        'active',
    ];
    public function regions () {
        return $this->hasMany(Region::class);
    }
    public function holidays () {
        return $this->hasManyThrough(HolidayRegion::class,Region::class);
    }
    public function translation($lang='en') {
        return $this->hasOne(CountryTranslation::class)
                ->where('lang', $lang);
    }
    public function translations() {
        return $this->hasMany(CountryTranslation::class);
    }
    public function descriptions() {
        return $this->hasMany(CountryDescription::class);
    }
    /**
     * 
     */
    public static function dig($longitude, $latitude, $save=true)
    {
        $digResult = [];
        $heremap = new HereMapRepository();
        $filters = [
            'prox' => "$latitude,$longitude,250",
            'mode' => 'retrieveAreas',
            'maxResults' => 1,
            'language' => 'en',
            'additionaldata' => 'IncludeShapeLevel,Country'
        ];
        $hereCountry = $heremap->reverse($filters);

        $country = Country::select('*')->addSelect(DB::raw(' asText(geometry) geom'))->whereRaw(" ST_INTERSECTS(geometry, (GeomFromText('POINT(" . $longitude . " " . $latitude . ")') ))")->first();
        
        if (!$country && $hereCountry) {
            $country = new Country();
            $country->iso_code = $hereCountry->location->address->country;
            $country->abbreviation = substr($country->iso_code, 0, 2);
            $country->longitude = $hereCountry->location->displayPosition->longitude;
            $country->latitude = $hereCountry->location->displayPosition->latitude;
            $country->active = 1;

            $countryGeometry = $heremap->simplify($hereCountry->location->shape['Value'], 'country');

            $bbox = $countryGeometry->getBBox();
            $country->sw_lat = $bbox["miny"];
            $country->sw_lon = $bbox["minx"];
            $country->ne_lat = $bbox["maxy"];
            $country->ne_lon = $bbox["maxx"];
            
            $centroid = $countryGeometry->centroid();
            $country->longitude = $centroid->coords[0];// $bbox["minx"] + (($bbox["maxx"]-$bbox["minx"])/2);
            $country->latitude = $centroid->coords[1];//$bbox["miny"] + (($bbox["maxy"]-$bbox["miny"])/2);

            $country->geometry = $countryGeometry->out('wkt');

            $digResult['geom'] = $country;

            if($save) {
                $country->geometry = DB::raw("ST_GEOMFROMTEXT('" . $countryGeometry->out('wkt') . "')");
                $country->save();
            }
            $countryName = new CountryTranslation();
            $countryName->country_id = $country->id;
            $additionalData = $hereCountry->location->address->additionalData;
            $name = null;
            foreach ($additionalData as $d) {
                if ($d['key'] == 'CountryName') {
                    $name = $d['value'];
                    break;
                }
            }
            $countryName->name = $name;
            $countryName->slug = Str::slug($name);
            $countryName->lang = 'en';

            if($save) {
                $countryName->save();
            }
            $digResult['names']['en'] = $countryName;

            foreach (config('app.langs') as $lang) {
                if ($lang != 'en') {
                    $filters = [
                        'prox' => "$latitude,$longitude,250",
                        'mode' => 'retrieveAreas',
                        'maxResults' => 1,
                        'language' => $lang
                    ];
                    $hereCountry = $heremap->reverse($filters);
                    $countryName = new CountryTranslation();
                    $countryName->country_id = $country->id;
                    $additionalData = $hereCountry->location->address->additionalData;
                    $name = null;
                    foreach ($additionalData as $d) {
                        if ($d['key'] == 'CountryName') {
                            $name = $d['value'];
                            break;
                        }
                    }
                    $countryName->name = $name;
                    $countryName->slug = Str::slug($name);
                    $countryName->lang = $lang;
                    
                    if($save) {
                        $countryName->save();
                    }
                    $digResult['names'][$lang] = $countryName;
                }
            }
        } else {
            $country->geometry = $country->geom;
            $digResult['geom'] = $country;
            foreach (config('app.langs') as $lang) {
                $digResult['names'][$lang] = $country->translation($lang)->first();
            }
        }
        if (!empty($hereCountry->matchQuality)) {
            $levels = ['state', 'county', 'city'];
            $hereLevels = $hereCountry->matchQuality->toArray();
            $hereLevelKeys = array_keys($hereLevels);
            foreach ($levels as $k => $l) {
                $found = false;
                foreach ($hereLevels as $key => $hl) {
                    if (in_array($key, $hereLevelKeys) && $hl != null && $l == $key) {
                        $found = true;
                    }
                }
                if (!$found) {
                    unset($levels[$k]);
                }
            }
            $digResult['levels']=$levels;
        }
        return $digResult;
    }
}
