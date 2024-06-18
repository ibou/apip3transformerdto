<?php

namespace App\Factory\Weapon;

use App\Entity\Weapon\WeaponExtra;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<WeaponExtra>
 */
final class WeaponExtraFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'active' => self::faker()->boolean(),
            'name' => self::faker()->text(255),
            'value' => self::faker()->randomNumber(),
        ];
    }

    protected static function getClass(): string
    {
        return WeaponExtra::class;
    }
}
