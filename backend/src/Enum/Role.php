<?php

declare(strict_types=1);

namespace App\Enum;

enum Role: string
{
    case User = 'User';
    case Moder = 'Moderator';
    case Admin = 'Administrator';
    public static function toArray(): array
    {
        return array_map(function ($case) {
            return $case->value;
        }, self::cases());
    }
}
