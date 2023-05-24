<?php

namespace App\Data\Enums;


enum CostPeriodUnitEnum: string
{
    case Day = 'day';
    case Weekend = 'weekend';
    case Week = 'week';
    case Stay = 'stay';
    case None = 'none';
}
