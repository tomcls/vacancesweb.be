<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostTranslation extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $with = ['cost'];
    public function cost (): BelongsTo {
        return $this->belongsTo(Cost::class);
    }
}
