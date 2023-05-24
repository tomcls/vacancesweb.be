<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayPrice extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $guarded = [];

    protected $casts = ['departure_date' => 'datetime'];
    protected $appends = ['departure_date_for_editing'];

    public function holiday () {
        return $this->belongsTo(Holiday::class);
    }
    public function getDateForHumansAttribute()
    {
        return $this->departure_date->format('d-m-Y');
    }
    public function getDepartureDateForEditingAttribute()
    {
        return $this->departure_date ? $this->departure_date->format('d-m-Y'): $this->departure_date;
    }
    public function setDepartureDateForEditingAttribute($value)
    {
         $this->departure_date = Carbon::parse($value);
    }
}
