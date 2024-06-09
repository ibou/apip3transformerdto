<?php

namespace App\Factory\Quest;

use App\Entity\Quest\Quest;
use App\Enum\Quest\QuestType;
use App\Factory\ItemFactory;
use App\Factory\Monster\MonsterFactory;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Quest>
 */
final class QuestFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'description' => self::faker()->text(),
            'failureConditions' => self::faker()->text(255),
            'level' => self::faker()->numberBetween(1, 8),
            'name' => self::faker()->text(255),
            'objective' => self::faker()->text(255),
            'type' => self::faker()->randomElement(QuestType::cases()),
            'client' => ClientFactory::new(),
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (Quest $quest): void {
            $this->withMonsters($quest);
            $this->withItems($quest);
        });
    }

    private function withMonsters(Quest $quest): void
    {
        $nbMonsters = self::faker()->numberBetween(1, 5);
        $monsters = MonsterFactory::randomSet($nbMonsters);

        foreach ($monsters as $monster) {
            QuestMonsterFactory::createOne([
                'quest' => $quest,
                'monster' => $monster,
            ]);
        }
    }

    private function withItems(Quest $quest): void
    {
        $nb = self::faker()->numberBetween(1, 5);
        $items = ItemFactory::randomSet($nb);

        foreach ($items as $item) {
            QuestItemFactory::createOne([
                'quest' => $quest,
                'item' => $item,
            ]);
        }
    }

    protected static function getClass(): string
    {
        return Quest::class;
    }
}
