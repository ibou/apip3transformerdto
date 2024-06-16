<?php

namespace App\Factory\Weapon;

use App\Entity\Weapon\WeaponMaterial;
use App\Enum\Weapon\WeaponMaterialType;
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
            'type' => self::faker()->randomElement(WeaponMaterialType::cases()),
        ];
    }

    protected static function getClass(): string
    {
        return WeaponMaterial::class;
    }
}
