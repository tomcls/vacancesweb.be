<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseDescription extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $with = ['house'];

    protected $fillable = [
        'lang',
        'description',
        'house_id'
    ];

    public function house () {
        return $this->belongsTo(House::class);
    }
}
