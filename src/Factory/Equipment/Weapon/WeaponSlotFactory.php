<?php

namespace App\Factory\Equipment\Weapon;

use App\Entity\Equipment\Weapon\WeaponSlot;
use App\Enum\Equipment\EquipmentSlotType;
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
            'type' => self::faker()->randomElement(EquipmentSlotType::cases()),
        ];
    }

    protected static function getClass(): string
    {
        return WeaponSlot::class;
    }
}
