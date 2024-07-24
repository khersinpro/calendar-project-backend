<?php

namespace App\Enum;

use App\Trait\ProvideEnumValues;

enum EventPaymentConditionEnum: string 
{
    use ProvideEnumValues;

    case PAYMENT = 'PAYMENT';
    case DEPOSIT = 'DEPOSIT';
    case FREE = 'FREE';
}