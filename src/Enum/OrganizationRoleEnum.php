<?php

namespace App\Enum;

use App\Trait\ProvideEnumValues;

enum OrganizationRoleEnum: string 
{
    use ProvideEnumValues;
    
    case ADMIN = 'ADMIN';
    case MANAGER = 'MANAGER';
    case USER = 'USER';
}