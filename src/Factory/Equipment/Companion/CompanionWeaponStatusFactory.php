<?php

namespace App\Factory\Equipment\Companion;

use App\Entity\Equipment\Companion\CompanionWeaponStatus;
use App\Enum\StatusEffect;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<CompanionWeaponStatus>
 */
final class CompanionWeaponStatusFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'status' => self::faker()->randomElement(StatusEffect::weaponStatusesCases()),
            'value' => self::faker()->numberBetween(1, 20),
        ];
    }

    protected static function getClass(): string
    {
        return CompanionWeaponStatus::class;
    }
}
