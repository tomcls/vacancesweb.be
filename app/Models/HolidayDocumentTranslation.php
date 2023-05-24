<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayDocumentTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $with = ['document'];
    
    public function document () {
        return $this->belongsTo(HolidayDocument::class,'holiday_document_id');
    }
}
