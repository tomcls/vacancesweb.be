<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionDescription extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'region_id',
        'lang',
        'description',
        'type',
    ];
}
