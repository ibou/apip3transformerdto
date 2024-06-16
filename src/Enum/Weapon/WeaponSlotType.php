<?php

namespace App\Enum\Weapon;

use App\Contract\Labelized;

enum WeaponSlotType: string implements Labelized
{
    case BASIC = 'basic';
    case RAMPAGE = 'rampage';

    public function label(): string
    {
        return match ($this) {
            self::BASIC => 'Basic',
            self::RAMPAGE => 'Rampage'
        };
    }

    public static function tryFromKiranicoLabel(string $label): self
    {
        return \str_contains($label, self::RAMPAGE->label())
            ? self::RAMPAGE : self::BASIC;
    }
}
