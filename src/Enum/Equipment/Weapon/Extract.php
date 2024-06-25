<?php

namespace App\Enum\Equipment\Weapon;

use App\Contract\Labelized;

enum Extract: string implements Labelized
{
    case RED = 'red';
    case WHITE = 'white';
    case ORANGE = 'orange';
    case GREEN = 'green';

    public function label(): string
    {
        return match ($this) {
            self::RED => 'Red',
            self::WHITE => 'White',
            self::ORANGE => 'Orange',
            self::GREEN => 'Green',
        };
    }
}
