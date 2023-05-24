<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HouseTitle extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $with = ['house'];
    /**
        * The attributes that are mass assignable.
        *
        * @var array<int, string>
        */
       protected $fillable = [
           'lang',
           'name',
           'slug',
           'house_id'
       ];
   
       public function house ():BelongsTo {
           return $this->belongsTo(House::class);
       }
}
