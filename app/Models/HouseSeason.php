<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HouseSeason extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    protected $casts = ['startdate' => 'datetime','enddate' => 'datetime'];
    protected $appends = ['startdate_for_editing','enddate_for_editing'];

    public function getStartdateForHumansAttribute()
    {
        return $this->startdate->format('d-m-Y');
    }
    public function getEnddateForHumansAttribute()
    {
        return $this->enddate->format('d-m-Y');
    }
    public function getStartdateForEditingAttribute()
    {
        return $this->startdate ? $this->startdate->format('d-m-Y'): $this->startdate;
    }
    public function getEnddateForEditingAttribute()
    {
        return $this->enddate ? $this->enddate->format('d-m-Y'): $this->enddate;
    }
    public function setStartdateForEditingAttribute($value)
    {
         $this->startdate = Carbon::parse($value);
    }
    public function setEnddateForEditingAttribute($value)
    {
         $this->enddate = Carbon::parse($value);
    }
}
