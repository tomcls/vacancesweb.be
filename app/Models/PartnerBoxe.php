<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerBoxe extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
        'partner_id', 'box_id', 'box_type'
    ];
    public $with = ['partner'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
