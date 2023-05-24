<?php

namespace App\Data\Enums;


enum CostPersonUnitEnum: string
{
    case Person = 'person';
    case PersonDay = 'person_day';
    case PersonWeek = 'person_week';
}
