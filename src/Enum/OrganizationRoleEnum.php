<?php

namespace App\Enum;

enum OrganizationRoleEnum: string 
{
    case ADMIN = 'ADMIN';
    case MANAGER = 'MANAGER';
    case USER = 'USER';
}