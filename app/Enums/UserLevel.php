<?php

namespace App\Enums;

enum UserLevel: string
{
    case JUNIOR = 'junior';
    case MIDDLE = 'middle';
    case SENIOR = 'senior';

    public function getValue(): string
    {
        return $this->value;
    }
}
