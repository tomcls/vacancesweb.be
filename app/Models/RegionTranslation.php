<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'region_id',
        'lang',
        'name',
        'slug',
        'path'
    ];
    public function region () {
        return $this->belongsTo(Region::class);
    }
}
