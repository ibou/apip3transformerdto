<?php

namespace App\Factory\Equipment\Companion;

use App\Entity\Equipment\Companion\CompanionArmor;
use App\Enum\Companion\CompanionType;
use App\Enum\StatusEffect;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<CompanionArmor>
 */
final class CompanionArmorFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'companion' => self::faker()->randomElement(CompanionType::cases()),
            'defense' => self::faker()->numberBetween(1, 20),
            'name' => self::faker()->text(255),
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (CompanionArmor $armor): void {
            $this->withResistances($armor);
        });
    }

    private function withResistances(CompanionArmor $armor): void
    {
        foreach (StatusEffect::armorStatusesCases() as $element) {
            CompanionArmorResistanceFactory::createOne(['armor' => $armor, 'element' => $element]);
        }
    }

    protected static function getClass(): string
    {
        return CompanionArmor::class;
    }
}
