<?php

namespace App\Factory\Monster;

use App\Entity\Monster\MonsterAilmentEffectiveness;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<MonsterAilmentEffectiveness>
 */
final class MonsterAilmentEffectivenessFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        $buildup = \sprintf('%d + %d > %d',
            self::faker()->numberBetween(0, 10),
            self::faker()->numberBetween(10, 50),
            self::faker()->numberBetween(100, 400)
        );

        $decay = \sprintf('%d / %d sec',
            self::faker()->numberBetween(0, 5),
            self::faker()->numberBetween(6, 10)
        );

        return [
            'buildup' => $buildup,
            'damage' => self::faker()->numberBetween(1, 10),
            'decay' => $decay,
            'duration' => \sprintf('%d sec', self::faker()->numberBetween(0, 20)),
        ];
    }

    protected static function getClass(): string
    {
        return MonsterAilmentEffectiveness::class;
    }
}
