<?php

namespace App\Enum\Monster;

use App\Contract\Labelized;

enum MonsterType: string implements Labelized
{
    case LARGE = 'lg';
    case SMALL = 'sm';

    public function label(): string
    {
        return match ($this) {
            self::LARGE => 'Large',
            self::SMALL => 'Small',
        };
    }
}
