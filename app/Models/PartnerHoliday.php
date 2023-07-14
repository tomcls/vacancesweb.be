<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PartnerHoliday extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'partner_id', 'holiday_id', 'sort', 'lang'
    ];

    public $with = ['partner','holiday'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    
    public function holiday(): BelongsTo
    {
        return $this->belongsTo(Holiday::class, 'holiday_id');
    }

    public function scopePartnerHolidays(Builder $query, $slug, $type= null): void
    {

        $query->select(DB::raw('
        holidays.id holiday_id, 
        holidays.active, 
        holidays.holiday_type_id, 
        holidays.longitude, 
        holidays.latitude, 
        holiday_titles.name as title, 
        holiday_titles.slug, 
        holiday_titles.privilege, 
        holiday_type_translations.name as type_name, 
        holidays.number_people, 
        region_translations.name region_name,
        country_translations.name country_name'))
            ->leftJoin('partners', 'partners.id', '=', DB::raw('partner_holidays.partner_id '))
            ->leftJoin('holidays', 'holidays.id', '=', DB::raw('partner_holidays.holiday_id '))
            ->leftJoin('holiday_regions', 'holiday_regions.holiday_id', '=', DB::raw('holidays.id '))
            ->leftJoin('regions', 'holiday_regions.region_id', '=', DB::raw('regions.id '))
            ->leftJoin('countries', 'regions.country_id', '=', DB::raw('countries.id '))
            ->leftJoin('region_translations', 'region_translations.region_id', '=', DB::raw('regions.id and region_translations.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('country_translations', 'country_translations.country_id', '=', DB::raw('countries.id and country_translations.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('holiday_titles', 'holiday_titles.holiday_id', '=', DB::raw('holidays.id and holiday_titles.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('holiday_types', 'holiday_types.id', '=', 'holidays.holiday_type_id')
            ->leftJoin('holiday_type_translations', 'holiday_type_translations.holiday_type_id', '=', DB::raw('holiday_types.id and holiday_type_translations.lang = \'' . App::currentLocale() . '\''))
            ->when($type, fn ($query, $type) => $query->where('holiday_types.code', '=', $type))
            ->where('holiday_titles.lang', '=', App::currentLocale())
            ->where('holidays.active', true)
            ->where('partners.code', '=', $slug)
            ->where(DB::raw('holidays.enddate'), '>=', now())
            ->groupBy('partner_holidays.holiday_id')
            ->orderBy('partner_holidays.sort');
    }
}
