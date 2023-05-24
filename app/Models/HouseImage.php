<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Storage;

class HouseImage extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'house_id',
        'sort',
        'origin',
    ];

    public function house ():BelongsTo {
        return $this->belongsTo(House::class);
    }
    public function path($size='large')
    {
        return Storage::disk('houseImages')->path($this->house_id.'/'.$size.'_'.$this->name);
    }
    public function url($size='large')
    {
        return Storage::disk('houseImages')->url($this->house_id.'/'.$size.'_'.$this->name);
    }
}
