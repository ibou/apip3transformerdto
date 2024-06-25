<?php

namespace App\Factory\Equipment\Weapon;

use App\Entity\Equipment\Weapon\WeaponMaterial;
use App\Enum\Equipment\EquipmentMaterialType;
use App\Factory\ItemFactory;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<WeaponMaterial>
 */
final class WeaponMaterialFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'item' => ItemFactory::repository()->random(),
            'quantity' => self::faker()->numberBetween(1, 8),
            'type' => self::faker()->randomElement(EquipmentMaterialType::cases()),
        ];
    }

    protected static function getClass(): string
    {
        return WeaponMaterial::class;
    }
}
