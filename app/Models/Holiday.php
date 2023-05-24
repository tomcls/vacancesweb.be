<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Holiday extends Model
{
    use HasFactory;

    protected $with = ['holidayType'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'holiday_type_id',
        'longitude',
        'latitude',
        'active'
    ];

    public function holidayTitles () {
        return $this->hasMany(HolidayTitle::class);
    }
    public function holidayTitle () {
        return $this->hasOne(HolidayTitle::class);
    }
    
    public function holidayDescriptions () {
        return $this->hasMany(HolidayDescription::class);
    }
    public function holidayDescription () {
        return $this->hasOne(HolidayDescription::class);
    }
    public function user () {
        return $this->belongsTo(User::class);
    }
    public function holidayType () {
        return $this->belongsTo(HolidayType::class);
    }

    public function cover () {
        return $this->hasOne(HolidayImage::class)->orderBy('sort');
    }
    public function regions() {
        return $this->hasMany(HolidayRegion::class);
    }

    /**
     * Get the holiday's image.
     */
    public function holidayImages(): HasMany
    {
        return $this->hasMany(HolidayImage::class)->orderBy('sort');
    }

    /**
     * Get the holiday's image.
     */
    public function holidayImage(): HasOne
    {
        return $this->hasOne(HolidayImage::class);
    }
    
    /**
     * Get the holiday's price.
     */
    public function holidayPrice(): HasMany
    {
        return $this->hasMany(HolidayPrice::class);
    }
    public function lowestPrice():HasOne {
        return $this->hasOne(HolidayPrice::class)->whereLowestPrice(true);
    }
    public function getPositionAttribute()
    {
        return $this->longitude ? true : false;
    }
    public function getHasPositionAttribute()
    {
        return $this->longitude ? true : false;
    }

    public function getDateCreatedAttribute() {
        return $this->created_at->format('d-m-Y');
    }
    public function getDateUpdatedAttribute() {
        return $this->updated_at->format('d-m-Y');
    }

    public function scopeFilter($query, array $filters)
    {
       return $query->when($filters['id'], fn ($query, $id) => $query->where('id', $id))
            ->when($filters['date-created-min'], fn ($query, $date) => $query->where('created_at', '>=', Carbon::parse($date)))
            ->when($filters['date-created-max'], fn ($query, $date) => $query->where('created_at', '<=', Carbon::parse($date)))
            ->when($filters['holiday_type_id'], fn ($query, $id) => $query->where('holiday_type_id', '=', $id))
            ->when($filters['lang'], fn ($query, $lang) =>
                $query->whereHas('holidayTitle',  fn ($subquery) => 
                    $subquery->where('lang', '=', $lang))
            ->when($filters['user_id'], fn ($query, $id) => $query->where('user_id', '=', $id))
            ->when($filters['active'], fn ($query, $active) => $query->where('active', '=', $active)))
            ->when($filters['search'], fn ($query, $search) =>
                $query->where('id', '=', $search)
                ->orWhereHas('holidayTitle',  fn ($subquery) => 
                    $subquery->where('name', 'like', '%' . $search . '%')));
    }
}
