<?php

namespace App\Factory\Equipment\Companion;

use App\Entity\Equipment\Companion\CompanionArmorResistance;
use App\Enum\StatusEffect;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<CompanionArmorResistance>
 */
final class CompanionArmorResistanceFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'element' => self::faker()->randomElement(StatusEffect::armorStatusesCases()),
            'value' => self::faker()->numberBetween(1, 10),
        ];
    }

    protected static function getClass(): string
    {
        return CompanionArmorResistance::class;
    }
}
