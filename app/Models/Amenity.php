<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Amenity extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $with = ['translation'];

    public function translation($lang = 'fr'): HasOne
    {
        return $this->hasOne(AmenityTranslation::class)->where('lang', $lang);
    }
}
