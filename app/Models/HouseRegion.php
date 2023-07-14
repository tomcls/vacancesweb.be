<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HouseRegion extends Model
{
    use HasFactory;

    // A slug can be a country name or region name (marrakech, tunis, mice, maroc)
    /**
     * @todo get only active and public houses
     */
    public function getHousesBySlugs($slugs, $lang = 'fr')
    {

        $query = $this::query()
            ->select(DB::raw('
        houses.acreage,
        houses.number_people,
        houses.rooms,
        house_titles.name house_name,
        house_titles.slug house_slug,
        house_type_translations.name house_type_name,
        house_regions.house_id, 
        house_regions.region_id,
        region_translations.name region_name, 
        region_translations.path region_path, 
        region_translations.slug region_slug, 
        countries.id country_id,
        country_translations.name coutry_name,
        country_translations.slug country_path,
        (select day_price from house_seasons where house_seasons.house_id = houses.id and enddate >= now() order by day_price asc limit 1) day_price,
        (select min_nights from house_seasons where house_seasons.house_id = houses.id and enddate >= now() order by day_price asc limit 1) min_nights,
        (select week_price from house_seasons where house_seasons.house_id = houses.id and enddate >= now() order by day_price asc limit 1) week_price,
        (select concat(\'small_\',name) from house_images where house_images.house_id = houses.id order by house_images.sort asc limit 1 ) cover'))
            ->leftJoin('houses', 'houses.id', '=', 'house_regions.house_id')
            ->leftJoin('house_types', 'house_types.id', '=', 'houses.house_type_id')
            ->leftJoin('house_type_translations', 'house_type_translations.house_type_id', '=', DB::raw('house_types.id and house_type_translations.lang = \'' . $lang . '\''))
            ->leftJoin('house_titles', 'house_titles.house_id', '=', 'houses.id')
            ->leftJoin('regions', 'regions.id', '=', 'house_regions.region_id')
            ->leftJoin('region_translations', 'region_translations.region_id', '=', DB::raw('regions.id and region_translations.lang = \'' . $lang . '\''))
            ->leftJoin('countries', 'countries.id', '=', 'regions.country_id')
            ->leftJoin('country_translations', 'country_translations.country_id', '=', DB::raw('countries.id and country_translations.lang = \'' . $lang . '\''))
            ->whereIn('region_translations.slug', $slugs)
            ->orWhereIn('country_translations.slug', $slugs)
            ->groupBy('house_regions.house_id');
        return $query->get();
    }
}
