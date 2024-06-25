<?php

namespace App\Factory\Equipment\Weapon;

use App\Entity\Equipment\Weapon\WeaponStatus;
use App\Enum\StatusEffect;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<WeaponStatus>
 */
final class WeaponStatusFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'status' => self::faker()->randomElement(StatusEffect::weaponStatusesCases()),
            'value' => self::faker()->numberBetween(1, 50),
        ];
    }

    protected static function getClass(): string
    {
        return WeaponStatus::class;
    }
}
