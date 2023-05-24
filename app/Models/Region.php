<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\HereMapRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Region extends Model
{
    use HasFactory;
    public $timestamps = false;
   // protected $with = ['Country'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'level',
        'longitude',
        'latitude',
        'sw_lat',
        'sw_lon',
        'ne_lat',
        'ne_lon',
        'geometry',
        'active',
    ];
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function holidays()
    {
        return $this->belongsToMany(Holiday::class, 'holiday_regions', 'region_id', 'holiday_id');
    }
    public function translation($lang = 'en')
    {
        return $this->hasOne(RegionTranslation::class)->where('lang', $lang);
    }
    public function translations()
    {
        return $this->hasMany(RegionTranslation::class);
    }
    public function descriptions()
    {
        return $this->hasMany(RegionDescription::class);
    }
    /**
     * ['country' => 
     *      ['geom' => [
     *          'longitude' => $lg,
     *          'latitude' => $lat,
     *          'geometry' => $geom
     *           ...],
     *            'names' => ['en' => CountryTranslation, 'fr'=> CountryTranslation, 'en'=> CountryTranslation],
     *      ]
     *  'regions'=> 
     *      [?'state'] => 
     *        ['geom' => [
     *            'longitude' => $lg,
     *            'latitude' => $lat,
     *            'geometry' => $geom
     *            ...],
     *             'names' => ['en' => RegionTranslation, 'fr'=> RegionTranslation, 'en'=> RegionTranslation]
     *        ],
     *      [?'county'] => [...],
     *      [?'city'] => [...],
     *  
     * ]
     */
    public static function dig($longitude, $latitude, $save = true)
    {
        $heremap = new HereMapRepository();
        $digResult = [];

        $hereCountry = new Country();
        $countryDig = $hereCountry->dig($longitude, $latitude, true);

        if (!empty($countryDig['levels'])) {
            $digResult['country'] = $countryDig;
            $paths = [];
            foreach ($countryDig['levels'] as $level) {
                $filters = [
                    'prox' => "$latitude,$longitude,250",
                    'mode' => 'retrieveAreas',
                    'maxResults' => 1,
                    'language' => 'en',
                    'additionaldata' => "IncludeShapeLevel,$level"
                ];
                $dbRegion = Region::select('*')->addSelect(DB::raw(' asText(geometry) geom'))->whereRaw(" level = '$level' and ST_INTERSECTS(geometry, (GeomFromText('POINT(" . $longitude . " " . $latitude . ")') ))")->first();
                if (!$dbRegion) {
                    $loc = $heremap->reverse($filters);
                    if(empty($loc->location->shape['Value']) && $level == 'city') {
                        $filters['additionaldata'] = "IncludeShapeLevel,postalCode";
                        $loc = $heremap->reverse($filters);
                    }
                    $region = new Region();
                    $region->country_id = $countryDig['geom']->id;
                    $region->level = $level;
                    $region->active = 1;

                    if(!empty($loc->location->shape['Value']))  {
                        $regionGeometry = $heremap->simplify($loc->location->shape['Value'], $level);

                        $bbox = $regionGeometry->getBBox();
                        $centroid = $regionGeometry->centroid();
                        $region->sw_lat = $bbox["miny"];
                        $region->sw_lon = $bbox["minx"];
                        $region->ne_lat = $bbox["maxy"];
                        $region->ne_lon = $bbox["maxx"];
                        $region->longitude = $centroid->coords[0];// $bbox["minx"] + (($bbox["maxx"]-$bbox["minx"])/2);
                        $region->latitude = $centroid->coords[1];//$bbox["miny"] + (($bbox["maxy"]-$bbox["miny"])/2);
                        $region->geometry = $regionGeometry->out('wkt');
    
                        if ($save) {
                            $region->geometry = DB::raw("ST_GEOMFROMTEXT('" . $regionGeometry->out('wkt') . "')");
                            $region->save();
                        }
                        $digResult['region'][$level]['geom'] = $region;
    
                        $regionName = new RegionTranslation();
                        $regionName->region_id = $region->id;
                        $regionName->name = $loc->location->address->{"$level"};
                        $regionName->slug = Str::slug($loc->location->address->{"$level"});
                        $regionName->lang = 'en';
                        $paths['en'] = ($paths['en'] ??  '') . '/' . Str::slug($loc->location->address->{"$level"});
                        $regionName->path = $paths['en'];
    
                        if ($save) {
                            $regionName->save();
                        }
    
                        $digResult['region'][$level]['names']['en'] = $regionName;
    
                        foreach (config('app.langs') as $lang) {
    
                            if ($lang != 'en') {
                                $filters = [
                                    'prox' => "$latitude,$longitude,250",
                                    'mode' => 'retrieveAreas',
                                    'maxResults' => 1,
                                    'language' => $lang
                                ];
                                $loc = $heremap->reverse($filters);
    
                                $regionName = new RegionTranslation();
                                $regionName->region_id = $region->id;
                                $regionName->name = $loc->location->address->{"$level"};
                                $regionName->slug = Str::slug($loc->location->address->{"$level"});
                                $regionName->lang = $lang;
                                $paths[$lang] = ($paths[$lang] ??  '') . '/' . Str::slug($loc->location->address->{"$level"});
                                $regionName->path = $paths[$lang];
    
                                if ($save) {
                                    $regionName->save();
                                }
    
                                $digResult['region'][$level]['names'][$lang] = $regionName;
                            }
                        }
                    }
                    
                } else {
                    $dbRegion->geometry = $dbRegion->geom;
                    $digResult['region'][$level]['geom'] = $dbRegion;
                    foreach (config('app.langs') as $lang) {
                        $paths[$lang] = ($paths[$lang] ??  '') . '/' . $dbRegion->translation($lang)->first()->slug;
                        $digResult['region'][$level]['names'][$lang] = $dbRegion->translation($lang)->first();
                    }
                }
            }
        }
        return $digResult;
    }
}
