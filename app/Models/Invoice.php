<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'payment_status', 'invoice_num', 'invoice_num','date_payed'
    ];
    protected $appends = ['date_payed_for_editing'];
  
    protected $casts = [
        'status' => Invoice::class,
        'date_payed' => 'datetime'
    ];

    public $with = ['user'];

    public function user ():BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDatePayedForEditingAttribute()
    {
        return $this->date_payed ? $this->date_payed->format('d-m-Y'): null;
    }
    public function setDatePayedForEditingAttribute($value)
    {
         $this->date_payed = Carbon::parse($value);
    }

    public function getDatePayedForHumansAttribute()
    {
        return $this->date_payed->format('d-m-Y h:i:s');
    }
    public function getDateCreatedForHumansAttribute()
    {
        return $this->created_at->format('d-m-Y h:i:s');
    }
}
