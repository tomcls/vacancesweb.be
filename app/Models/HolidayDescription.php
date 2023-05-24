<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayDescription extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $with = ['holiday'];

    protected $fillable = [
        'lang',
        'description',
        'holiday_id'
    ];

    public function holiday () {
        return $this->belongsTo(Holiday::class);
    }
}
