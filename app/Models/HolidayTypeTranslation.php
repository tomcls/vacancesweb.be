<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayTypeTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    public function holiday () {
        return $this->belongsTo(Holiday::class);
    }
}
