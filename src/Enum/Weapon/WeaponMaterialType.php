<?php

namespace App\Enum\Weapon;

use App\Contract\Labelized;

enum WeaponMaterialType: string implements Labelized
{
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
