<?php

namespace App\Enum;

enum WorkingDayStatusEnum: string 
{
    case WORKING = 'WORKING';
    case NOT_WORKING = 'NOT_WORKING';
    case VACATION = 'VACATION';
    case HOLIDAY = 'HOLIDAY';
    case OTHER = 'OTHER';
}