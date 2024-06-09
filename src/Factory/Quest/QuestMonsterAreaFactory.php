<?php

namespace App\Factory\Quest;

use App\Entity\Quest\QuestMonsterArea;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<QuestMonsterArea>
 */
final class QuestMonsterAreaFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'numero' => self::faker()->numberBetween(1, 100),
            'percentChance' => self::faker()->numberBetween(1, 100),
        ];
    }

    protected static function getClass(): string
    {
        return QuestMonsterArea::class;
    }
}
