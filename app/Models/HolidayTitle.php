<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HolidayTitle extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $with = ['holiday'];
 /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lang',
        'name',
        'slug',
        'privilege',
        'holiday_id'
    ];

    public function holiday ():BelongsTo {
        return $this->belongsTo(Holiday::class);
    }

    public function scopeFilter($query, array $filters)
    {
       return $query->when($filters['name'], fn ($query, $name) => $query->where('name', 'like', '%' . $name . '%'))
            ->when($filters['lang'], fn ($query, $lang) => $query->where('lang', '=', $lang))
            ->when($filters['search'], fn ($query, $search) =>
                $query->where('name', 'like', '%' . $search . '%'));
    }
}
