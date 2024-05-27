<?php

namespace App\Factory\Monster;

use App\Entity\Monster\MonsterItem;
use App\Enum\Item\ItemDropMethod;
use App\Enum\Quest\QuestRank;
use App\Factory\ItemFactory;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<MonsterItem>
 */
final class MonsterItemFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'amount' => self::faker()->randomNumber(),
            'item' => ItemFactory::randomOrCreate(),
            'method' => self::faker()->randomElement(ItemDropMethod::cases()),
            'questRank' => self::faker()->randomElement(QuestRank::cases()),
            'rate' => self::faker()->randomNumber(),
        ];
    }

    protected static function getClass(): string
    {
        return MonsterItem::class;
    }
}
