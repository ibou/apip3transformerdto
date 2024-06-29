<?php

namespace App\Enum\Companion;

use App\Contract\Labelized;
use App\Trait\Enum\FromLabelTrait;

enum CompanionWeaponType: string implements Labelized
{
    use FromLabelTrait;

    case BLUNT = 'blunt';
    case SEVERING = 'severing';

    public function label(): string
    {
        return match ($this) {
            self::BLUNT => 'Blunt',
            self::SEVERING => 'Severing'
        };
    }
}
