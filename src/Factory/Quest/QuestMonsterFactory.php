<?php

namespace App\Factory\Quest;

use App\Entity\Quest\QuestMonster;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<QuestMonster>
 */
final class QuestMonsterFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (QuestMonster $monster): void {
            $this->withMonsterAttributes($monster);
            $this->withSizes($monster);
            $this->withAreas($monster);
        });
    }

    private function withMonsterAttributes(QuestMonster $monster): void
    {
        QuestMonsterAttributeFactory::createMany(self::faker()->numberBetween(1, 5), [
            'monster' => $monster,
        ]);
    }

    private function withSizes(QuestMonster $monster): void
    {
        QuestMonsterSizeFactory::createMany(self::faker()->numberBetween(1, 5), [
            'monster' => $monster,
        ]);
    }

    private function withAreas(QuestMonster $monster): void
    {
        QuestMonsterAreaFactory::createMany(self::faker()->numberBetween(1, 5), [
            'monster' => $monster,
        ]);
    }

    protected static function getClass(): string
    {
        return QuestMonster::class;
    }
}
