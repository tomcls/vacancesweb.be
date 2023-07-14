<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\App;

class HolidayType extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function translation ():HasMany {
        return $this->hasMany(HolidayTypeTranslation::class)->whereLang(App::currentLocale());
    }
}
