<?php

namespace App\Factory\Quest;

use App\Entity\Quest\QuestMonsterSize;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<QuestMonsterSize>
 */
final class QuestMonsterSizeFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        $size = \sprintf('%d.%d',
            self::faker()->numberBetween(1, 9999),
            self::faker()->numberBetween(1, 99)
        );

        return [
            'percentChance' => self::faker()->numberBetween(1, 4),
            'size' => $size,
        ];
    }

    protected static function getClass(): string
    {
        return QuestMonsterSize::class;
    }
}
