<?php
  
namespace App\Data\Enums;

enum InvoiceTransactionTypeEnum:string {
    case HouseHighlight = 'house_highlight';
    case HousePackage = 'house_package';
    case HolidayPurchase = 'holiday_purchase';
    case HousePublication = 'house_publication';
}