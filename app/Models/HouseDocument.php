<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;
class HouseDocument extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $guarded = [];

    public function url() {
        return Storage::disk('houseDocuments')->url($this->house_id.'/'.$this->name);
    }
}
