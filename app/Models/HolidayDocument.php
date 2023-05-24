<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;

class HolidayDocument extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function url() {
        return Storage::disk('holidayDocuments')->url($this->holiday_id.'/'.$this->name);
    }
}
