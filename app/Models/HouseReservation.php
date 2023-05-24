<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseReservation extends Model
{
    use HasFactory;

    protected $guarded = []; 
       
    protected $fillable = [
       'user_id', 'house_id', 'startdate', 'enddate',
    ];
    public $with = ['house','user'];

    protected $casts = [
        'startdate' => 'datetime',
        'enddate' => 'datetime'
    ];

    protected $appends = ['startdate_for_editing','enddate_for_editing'];

    public function house ():BelongsTo {
        return $this->belongsTo(House::class, 'house_id');
    }
    public function user ():BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
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
    public function getStartDateForHumansAttribute()
    {
        return $this->startdate->format('d-m-Y h:i:s');
    }
    public function getEndDateForHumansAttribute()
    {
        return $this->enddate->format('d-m-Y h:i:s');
    }
    public function getCreatedForHumansAttribute()
    {
        return $this->created_at->format('d-m-Y h:i:s');
    }
}
