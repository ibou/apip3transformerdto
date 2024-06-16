<?php

namespace App\Factory\Weapon;

use App\Entity\Weapon\WeaponSlot;
use App\Enum\Weapon\WeaponSlotType;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<WeaponSlot>
 */
final class WeaponSlotFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'quantity' => self::faker()->numberBetween(1, 10),
            'type' => self::faker()->randomElement(WeaponSlotType::cases()),
        ];
    }

    protected static function getClass(): string
    {
        return WeaponSlot::class;
    }
}
