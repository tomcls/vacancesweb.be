<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerHoliday extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'partner_id', 'holiday_id', 'sort', 'lang'
    ];

    public $with = ['partner','holiday'];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    
    public function holiday(): BelongsTo
    {
        return $this->belongsTo(Holiday::class, 'holiday_id');
    }
}
