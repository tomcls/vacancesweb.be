<?php
  
namespace App\Data\Enums;

enum InvoicePaymentStatusEnum:string {
    case Pending = 'pending';
    case Success = 'success';
    case Failed = 'failed';
    case Canceled = 'canceled';
}