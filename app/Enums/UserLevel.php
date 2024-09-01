<?php

namespace App\Enums;

class UserLevel
{
    const JUNIOR = 'junior';
    const MIDDLE = 'middle';
    const SENIOR = 'senior';

    public static function all()
    {
        return [
            self::JUNIOR,
            self::MIDDLE,
            self::SENIOR,
        ];
    }
}
