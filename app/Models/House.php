<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class House extends Model
{
    use HasFactory;
    public $with = ['houseType','cover'];
    protected $casts = ['dateFrom' => 'datetime'];
    public function cover () {
        return $this->hasOne(HouseImage::class)->orderBy('sort','asc');
    }
    /**
     * Get the house's image.
     */
    public function houseImages(): HasMany
    {
        return $this->hasMany(HouseImage::class)->orderBy('sort');
    }
    /**
     * Get the house's image.
     */
    public function houseImage(): HasOne
    {
        return $this->hasOne(HouseImage::class);
    }
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class,'house_amenities');
    }
    public function costs(): BelongsToMany
    {
        return $this->belongsToMany(Cost::class,'house_costs');
    }

    public function houseTitle () {
        return $this->hasOne(HouseTitle::class);
    }

    public function houseDescription () {
        return $this->hasOne(HouseDescription::class);
    }

    public function houseTitles () {
        return $this->hasMany(HouseTitle::class);
    }

    public function houseDescriptions () {
        return $this->hasMany(HouseDescription::class);
    }

    public function getDateCreatedAttribute() {
        return $this->created_at->format('d-m-Y');
    }
    public function getDateUpdatedAttribute() {
        return $this->updated_at->format('d-m-Y');
    }
    public function houseType () {
        return $this->belongsTo(HouseType::class);
    }
    public function user () {
        return $this->belongsTo(User::class);
    }
    public function getPositionAttribute()
    {
        return $this->longitude ? true : false;
    }
    public function getHasPositionAttribute()
    {
        return $this->longitude ? true : false;
    }
}
