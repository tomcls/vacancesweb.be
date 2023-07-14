<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class PartnerArticle extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'partner_id', 'post_id', 'sort', 'lang'
    ];

    public $with = ['partner'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function scopePartnerPosts(Builder $query, $slug): void
    {
        $query->select(DB::raw('*'))->leftJoin('partners', 'partners.id', '=', DB::raw('partner_articles.partner_id '))
            ->where('partners.code', '=', $slug)
            ->groupBy('partner_articles.post_id')
            ->orderBy('partner_articles.sort');
    }
}
