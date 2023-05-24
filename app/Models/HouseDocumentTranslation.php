<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseDocumentTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    public $with = ['document'];
    
    public function document () {
        return $this->belongsTo(HouseDocument::class,'house_document_id');
    }
}
