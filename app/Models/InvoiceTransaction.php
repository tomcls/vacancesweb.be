<?php

namespace App\Models;

use App\Data\Enums\InvoiceTransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceTransaction extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['invoice_id','reference','price' ,'type','promo_id'];

    public $with = ['invoice'];

    protected $casts = [
        'type' => InvoiceTransactionTypeEnum::class,
    ];

    public function invoice ():BelongsTo {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }


}
