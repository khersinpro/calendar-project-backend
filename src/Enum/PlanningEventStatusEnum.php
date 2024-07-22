<?php

namespace App\Enum;

enum PlanningEventStatusEnum: string
{
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
    case CANCELLED = 'CANCELLED';
}