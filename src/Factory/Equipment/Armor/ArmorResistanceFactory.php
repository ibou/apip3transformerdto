<?php

namespace App\Factory\Equipment\Armor;

use App\Entity\Equipment\Armor\ArmorResistance;
use App\Enum\StatusEffect;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<ArmorResistance>
 */
final class ArmorResistanceFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'element' => self::faker()->randomElement(StatusEffect::armorStatusesCases()),
            'value' => self::faker()->numberBetween(1, 100),
        ];
    }

    protected static function getClass(): string
    {
        return ArmorResistance::class;
    }
}
