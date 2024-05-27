<?php

namespace App\Factory\Monster;

use App\Entity\Monster\MonsterBodyPartWeakness;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<MonsterBodyPartWeakness>
 */
final class MonsterBodyPartWeaknessFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'elementDragon' => self::faker()->numberBetween(1, 100),
            'elementFire' => self::faker()->numberBetween(1, 100),
            'elementIce' => self::faker()->numberBetween(1, 100),
            'elementStun' => self::faker()->numberBetween(1, 100),
            'elementThunder' => self::faker()->numberBetween(1, 100),
            'elementWater' => self::faker()->numberBetween(1, 100),
            'hitShell' => self::faker()->numberBetween(1, 100),
            'hitSlash' => self::faker()->numberBetween(1, 100),
            'hitStrike' => self::faker()->numberBetween(1, 100),
        ];
    }

    protected static function getClass(): string
    {
        return MonsterBodyPartWeakness::class;
    }
}
