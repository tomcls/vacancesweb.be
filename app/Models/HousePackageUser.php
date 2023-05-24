<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousePackageUser extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'house_package_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(HousePackage::class, 'house_package_id');
    }
}
