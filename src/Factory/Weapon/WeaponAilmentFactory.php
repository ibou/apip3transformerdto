<?php

namespace App\Factory\Weapon;

use App\Entity\Weapon\WeaponAilment;
use App\Enum\Ailment;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<WeaponAilment>
 */
final class WeaponAilmentFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'ailment' => self::faker()->randomElement(Ailment::cases()),
            'value' => self::faker()->numberBetween(1, 50),
        ];
    }

    protected static function getClass(): string
    {
        return WeaponAilment::class;
    }
}
