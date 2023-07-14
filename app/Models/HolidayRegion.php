<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HolidayRegion extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $with = ['Holiday','Region'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'holiday_id',
        'region_id',
    ];
    public function region() {
        return $this->belongsTo(Region::class);
    }
    public function holiday() {
        return $this->belongsTo(Holiday::class);
    }
    // A slug can be a country name or region name (marrakech, tunis, mice, maroc)
    /**
     * @todo get only active and public holidays
     */
    public function getHolidaysBySlugs($slugs, $lang = 'fr')
    {

        $query = $this::query()
            ->select(DB::raw('
        holiday_titles.name holiday_name,
        holiday_titles.slug holiday_slug,
        holiday_type_translations.name holiday_type_name,
        holiday_regions.holiday_id, 
        holiday_regions.region_id,
        region_translations.name region_name, 
        region_translations.path region_path, 
        region_translations.slug region_slug, 
        countries.id country_id,
        country_translations.name coutry_name,
        country_translations.slug country_path,
        (select price_customer from holiday_prices where holiday_prices.holiday_id = holidays.id and departure_date >= now() order by price_customer asc limit 1) price_customer,
        (select departure_from from holiday_prices where holiday_prices.holiday_id = holidays.id and departure_date >= now() order by price_customer asc limit 1) departure_from,
        (select info from holiday_prices where holiday_prices.holiday_id = holidays.id and departure_date >= now() order by price_customer asc limit 1) info,
        (select departure_date from holiday_prices where holiday_prices.holiday_id = holidays.id and departure_date >= now() order by price_customer asc limit 1) departure_date,
        (select duration_days from holiday_prices where holiday_prices.holiday_id = holidays.id and departure_date >= now() order by price_customer asc limit 1) duration_days,
        (select duration_nights from holiday_prices where holiday_prices.holiday_id = holidays.id and departure_date >= now() order by price_customer asc limit 1) duration_nights,
        (select concat(\'small_\',name) from holiday_images where holiday_images.holiday_id = holidays.id order by holiday_images.sort asc limit 1 ) cover'))
            ->leftJoin('holidays', 'holidays.id', '=', 'holiday_regions.holiday_id')
            ->leftJoin('holiday_types', 'holiday_types.id', '=', 'holidays.holiday_type_id')
            ->leftJoin('holiday_type_translations', 'holiday_type_translations.holiday_type_id', '=', DB::raw('holiday_types.id and holiday_type_translations.lang = \'' . $lang . '\''))
            ->leftJoin('holiday_titles', 'holiday_titles.holiday_id', '=', 'holidays.id')
            ->leftJoin('regions', 'regions.id', '=', 'holiday_regions.region_id')
            ->leftJoin('region_translations', 'region_translations.region_id', '=', DB::raw('regions.id and region_translations.lang = \'' . $lang . '\''))
            ->leftJoin('countries', 'countries.id', '=', 'regions.country_id')
            ->leftJoin('country_translations', 'country_translations.country_id', '=', DB::raw('countries.id and country_translations.lang = \'' . $lang . '\''))
            ->whereIn('region_translations.slug', $slugs)
            ->orWhereIn('country_translations.slug', $slugs)
            ->groupBy('holiday_regions.holiday_id');

        return $query->get();
    }
}
