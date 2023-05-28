<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseIcal extends Model
{
    use HasFactory;
    protected $fillable = [
        'house_id', 'url', 'hash'
    ];
    public $with = ['house'];

    public function house ():BelongsTo {
        return $this->belongsTo(House::class, 'house_id');
    }
    public function getCreatedForHumansAttribute()
    {
        return $this->created_at->format('d-m-Y h:i:s');
    }
}
