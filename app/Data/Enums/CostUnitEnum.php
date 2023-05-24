<?php

namespace App\Data\Enums;


enum CostUnitEnum: string
{
    case Day = 'day';
    case Weekend = 'weekend';
    case Week = 'week';
    case Stay = 'stay';
    case Person = 'person';
    case PersonDay = 'person_day';
    case PersonWeek = 'person_week';
    case MeterCube = 'meter_cube';
    case Kwh = 'kwh';
    case Liter = 'liter';
    case Hour = 'hour';
    case Use = 'use';
}
