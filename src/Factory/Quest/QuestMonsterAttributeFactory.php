<?php

namespace App\Factory\Quest;

use App\Entity\Quest\QuestMonsterAttribute;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<QuestMonsterAttribute>
 */
final class QuestMonsterAttributeFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        $attack = \sprintf('%d.%d',
            self::faker()->numberBetween(1, 99),
            self::faker()->numberBetween(1, 99)
        );

        $parts = \sprintf('%d.%d',
            self::faker()->numberBetween(1, 99),
            self::faker()->numberBetween(1, 99)
        );

        $ailment = \sprintf('%d/%d',
            self::faker()->numberBetween(1, 9),
            self::faker()->numberBetween(1, 12345678912)
        );

        $stamina = \sprintf('%d.%d',
            self::faker()->numberBetween(1, 99),
            self::faker()->numberBetween(1, 99)
        );

        $mount = \sprintf('%d.%d',
            self::faker()->numberBetween(1, 9),
            self::faker()->numberBetween(1, 99)
        );

        $stun = \sprintf('%d.%d',
            self::faker()->numberBetween(1, 9),
            self::faker()->numberBetween(1, 99)
        );

        return [
            'ailment' => $ailment,
            'attack' => $attack,
            'defense' => self::faker()->randomNumber(),
            'hps' => [self::faker()->randomFloat(), self::faker()->randomFloat()],
            'mount' => $mount,
            'nbPlayers' => self::faker()->randomNumber(),
            'parts' => $parts,
            'stamina' => $stamina,
            'stun' => $stun,
        ];
    }

    protected static function getClass(): string
    {
        return QuestMonsterAttribute::class;
    }
}
