<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryDescription extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'country_id',
        'lang',
        'description',
        'type',
    ];
}
