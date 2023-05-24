<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousePromoTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'house_promo_id', 'name', 'lang'
    ];
    
    public function promo(): BelongsTo
    {
        return $this->belongsTo(HousePromo::class,'house_promo_id');
    }
}
