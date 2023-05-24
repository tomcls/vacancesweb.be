<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousePackageTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'house_package_id', 'name', 'lang'
    ];
    
    public function package(): BelongsTo
    {
        return $this->belongsTo(HousePackage::class,'house_package_id');
    }
}
