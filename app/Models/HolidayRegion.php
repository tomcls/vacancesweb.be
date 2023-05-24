<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
