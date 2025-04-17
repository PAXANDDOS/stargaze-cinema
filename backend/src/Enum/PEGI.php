<?php

declare(strict_types=1);

namespace App\Enum;

enum PEGI: string
{
    case PG3 = 'PEGI 3';
    case PG7 = 'PEGI 7';
    case PG12 = 'PEGI 12';
    case PG16 = 'PEGI 16';
    case PG18 = 'PEGI 18';
    public static function getRandom(): self
    {
        $cases = self::cases();

        return $cases[array_rand($cases)];
    }

    public static function toArray(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, self::cases());
    }
}
