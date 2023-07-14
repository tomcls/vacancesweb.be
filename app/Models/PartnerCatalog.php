<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\App;

class PartnerCatalog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'partner_id', 'holiday_id', 'sort', 'lang'
    ];

    public $with = ['partner', 'holiday'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    public function holiday(): BelongsTo
    {
        return $this->belongsTo(Holiday::class, 'holiday_id');
    }

    public function scopePartnerHolidays(Builder $query, $slug): void
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
        region_translations.name region_name'))
            ->leftJoin('partners', 'partners.id', '=', DB::raw('partner_catalogs.partner_id '))
            ->leftJoin('holidays', 'holidays.id', '=', DB::raw('partner_catalogs.holiday_id '))
            ->leftJoin('holiday_regions', 'holiday_regions.holiday_id', '=', DB::raw('holidays.id '))
            ->leftJoin('regions', 'holiday_regions.region_id', '=', DB::raw('regions.id '))
            ->leftJoin('region_translations', 'region_translations.region_id', '=', DB::raw('regions.id and region_translations.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('holiday_titles', 'holiday_titles.holiday_id', '=', DB::raw('holidays.id and holiday_titles.lang = \'' . App::currentLocale() . '\''))
            ->leftJoin('holiday_types', 'holiday_types.id', '=', 'holidays.holiday_type_id')
            ->leftJoin('holiday_type_translations', 'holiday_type_translations.holiday_type_id', '=', DB::raw('holiday_types.id and holiday_type_translations.lang = \'' . App::currentLocale() . '\''))
            ->where('holiday_titles.lang', '=', App::currentLocale())
            ->where('holidays.active', true)
            ->where('partners.code', '=', $slug)
            ->where(DB::raw('holidays.enddate'), '>=', now())
            ->groupBy('holidays.id')
            ->orderBy('partner_catalogs.sort');
    }
}
