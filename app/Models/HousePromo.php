<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousePromo extends Model
{
    use HasFactory;
    protected $garded = [];
    public $timestamps = false;
    
}
