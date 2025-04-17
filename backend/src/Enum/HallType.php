<?php

declare(strict_types=1);

namespace App\Enum;

enum HallType: string
{
    case H2D = '2D';
    case H3D = '3D';
    case IMAX = 'IMAX 3D';
    case H4DX = '4DX';
    case H5D = '5D';
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
