<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousePublication extends Model
{
    use HasFactory;
    protected $fillable = [
        'house_id', 'startdate', 'enddate'
    ];

    protected $appends = ['startdate_for_editing','enddate_for_editing'];

    protected $casts = [
        'startdate' => 'datetime',
        'enddate' => 'datetime'
    ];

    public $with = ['house'];

    public function house ():BelongsTo {
        return $this->belongsTo(House::class, 'house_id');
    }

    public function getStartDateForEditingAttribute()
    {
        return $this->startdate ? $this->startdate->format('d-m-Y'): null;
    }
    public function setStartDateForEditingAttribute($value)
    {
         $this->startdate = Carbon::parse($value);
    }
    public function getEndDateForEditingAttribute()
    {
        return $this->enddate ? $this->enddate->format('d-m-Y'): null;
    }
    public function setEndDateForEditingAttribute($value)
    {
         $this->enddate = Carbon::parse($value);
    }

    public function getCreatedForHumansAttribute()
    {
        return $this->created_at->format('d-m-Y h:i:s');
    }

    public function getStartDateForHumansAttribute()
    {
        return $this->startdate->format('d-m-Y h:i:s');
    }
    public function getEndDateForHumansAttribute()
    {
        return $this->enddate->format('d-m-Y h:i:s');
    }
}
