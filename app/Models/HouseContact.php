<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseContact extends Model
{
    use HasFactory;
    public $with = ['user'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
