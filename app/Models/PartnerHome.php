<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Storage;

class PartnerHome extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'partner_id', 'hero_id', 'hero_type', 'testimonial_url', 'conference_url', 'lang'
    ];
    public $with = ['partner'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    public function scopeHero(Builder $query, $slug, $lang = 'fr'): void
    {
        $query->leftJoin('partners', 'partners.id', '=', DB::raw('partner_homes.partner_id '))
            ->where('partners.code', '=', $slug)
            ->where('partner_homes.lang', '=', $lang)
            ->groupBy('partner_homes.id');
    }
    public function url($size = 'large')
    {
        return Storage::disk('partners')->url($this->partner_id . '/' . $size . '_' . $this->image);
    }
}
