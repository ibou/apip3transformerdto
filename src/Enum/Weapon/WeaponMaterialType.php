<?php

namespace App\Enum\Weapon;

use App\Contract\Labelized;
use App\Trait\Enum\FromLabelTrait;

enum WeaponMaterialType: string implements Labelized
{
    use FromLabelTrait;

    case FORGING = 'forging';
    case UPGRADE = 'upgrade';

    public function label(): string
    {
        return match ($this) {
            self::FORGING => 'Forging Materials',
            self::UPGRADE => 'Upgrade Materials'
        };
    }
}
