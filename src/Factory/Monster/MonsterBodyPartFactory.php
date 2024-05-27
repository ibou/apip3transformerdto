<?php

namespace App\Factory\Monster;

use App\Entity\Monster\MonsterBodyPart;
use App\Enum\Weapon\Extract;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<MonsterBodyPart>
 */
final class MonsterBodyPartFactory extends ModelFactory
{
    public const array PARTS_NAME = ['head', 'body', 'wing'];

    protected function getDefaults(): array
    {
        return [
            'part' => self::faker()->randomElement(self::PARTS_NAME),
            'hp' => self::faker()->randomNumber(),
            'extract' => self::faker()->randomElement(Extract::cases()),
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (MonsterBodyPart $monsterBodyPart): void {
            $this->withWeakness($monsterBodyPart);
        })
        ;
    }

    private function withWeakness(MonsterBodyPart $part): void
    {
        for ($i = 0; $i < self::faker()->numberBetween(3, 5); ++$i) {
            MonsterBodyPartWeaknessFactory::createOne([
                'state' => $i,
                'part' => $part,
            ]);
        }
    }

    protected static function getClass(): string
    {
        return MonsterBodyPart::class;
    }
}
