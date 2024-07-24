<?php

namespace App\Enum;

use App\Trait\ProvideEnumValues;

enum WorkingDayStatusEnum: string 
{
    use ProvideEnumValues;
    
    case WORKING = 'WORKING';
    case NOT_WORKING = 'NOT_WORKING';
    case VACATION = 'VACATION';
    case HOLIDAY = 'HOLIDAY';
    case OTHER = 'OTHER';
}