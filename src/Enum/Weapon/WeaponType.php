<?php

namespace App\Enum\Weapon;

use App\Contract\Labelized;

enum WeaponType: string implements Labelized
{
    case GREAT_SWORD = 'great_sword';
    case SWORD_AND_SHIELD = 'sword_and_shield';
    case DUAL_BLADES = 'dual_blades';
    case LONG_SWORD = 'long_sword';
    case HAMMER = 'hammer';
    case HUNTING_HORN = 'hunting_horn';
    case LANCE = 'lance';
    case GUNLANCE = 'gunlance';
    case SWITCH_AXE = 'switch_axe';
    case CHARGE_BLADE = 'charge_blade';
    case INSECT_GLAIVE = 'insect_glaive';
    case BOW = 'bow';
    case HEAVY_BOWGUN = 'heavy_bowgun';
    case LIGHT_BOWGUN = 'light_bowgun';

    public function label(): string
    {
        return match ($this) {
            self::GREAT_SWORD => 'Great Sword',
            self::SWORD_AND_SHIELD => 'Sword & Shield',
            self::DUAL_BLADES => 'Dual Blades',
            self::LONG_SWORD => 'Long Sword',
            self::HAMMER => 'Hammer',
            self::HUNTING_HORN => 'Hunting Horn',
            self::LANCE => 'Lance',
            self::GUNLANCE => 'Gunlance',
            self::SWITCH_AXE => 'Switch Axe',
            self::CHARGE_BLADE => 'Charge Blade',
            self::INSECT_GLAIVE => 'Insect Glaive',
            self::BOW => 'Bow',
            self::HEAVY_BOWGUN => 'Heavy Bowgun',
            self::LIGHT_BOWGUN => 'Light Bowgun',
        };
    }

    public function withSharpness(): bool
    {
        return \in_array($this, [
            self::GREAT_SWORD,
            self::SWORD_AND_SHIELD,
            self::DUAL_BLADES,
            self::HAMMER,
            self::HUNTING_HORN,
            self::LANCE,
            self::GUNLANCE,
            self::SWITCH_AXE,
            self::CHARGE_BLADE,
            self::INSECT_GLAIVE,
        ]);
    }

    public function kiranicoView(): int
    {
        return match ($this) {
            self::GREAT_SWORD => 0,
            self::SWORD_AND_SHIELD => 1,
            self::DUAL_BLADES => 2,
            self::LONG_SWORD => 3,
            self::HAMMER => 4,
            self::HUNTING_HORN => 5,
            self::LANCE => 6,
            self::GUNLANCE => 7,
            self::SWITCH_AXE => 8,
            self::CHARGE_BLADE => 9,
            self::INSECT_GLAIVE => 10,
            self::BOW => 11,
            self::HEAVY_BOWGUN => 12,
            self::LIGHT_BOWGUN => 13,
        };
    }
}
