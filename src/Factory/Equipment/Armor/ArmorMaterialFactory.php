<?php

namespace App\Factory\Equipment\Armor;

use App\Entity\Equipment\Armor\ArmorMaterial;
use App\Enum\Equipment\EquipmentMaterialType;
use App\Factory\ItemFactory;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<ArmorMaterial>
 */
final class ArmorMaterialFactory extends ModelFactory
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
        return ArmorMaterial::class;
    }
}
