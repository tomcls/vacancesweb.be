<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousePackagePublication extends Model
{
    use HasFactory;
    protected $garded = [];

    public function publication(): BelongsTo
    {
        return $this->belongsTo(HousePublication::class,'house_publication_id');
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(HousePackage::class,'house_package_id');
    }
}
