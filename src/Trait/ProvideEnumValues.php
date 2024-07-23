<?php

namespace App\Trait;

trait ProvideEnumValues
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}