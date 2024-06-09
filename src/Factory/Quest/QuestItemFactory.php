<?php

namespace App\Factory\Quest;

use App\Entity\Quest\QuestItem;
use App\Enum\Quest\QuestItemType;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<QuestItem>
 */
final class QuestItemFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'percentChance' => self::faker()->numberBetween(1, 100),
            'quantity' => self::faker()->numberBetween(1, 50),
            'type' => self::faker()->randomElement(QuestItemType::cases()),
        ];
    }

    protected static function getClass(): string
    {
        return QuestItem::class;
    }
}
