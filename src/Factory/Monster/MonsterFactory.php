<?php

namespace App\Factory\Monster;

use App\Entity\Monster\Monster;
use App\Enum\Monster\MonsterType;
use App\Enum\StatusEffect;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Monster>
 */
final class MonsterFactory extends ModelFactory
{
    private const string MONSTER_URL = 'http://cdn.kiranico.net/file/kiranico/mhrise-web/images/icons/em132_05.png';

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->unique()->name,
            'type' => self::faker()->randomElement(MonsterType::cases()),
            'description' => self::faker()->text(255),
            'imageUrl' => self::MONSTER_URL,
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (Monster $monster): void {
            $this->withBodyPart($monster);
            $this->withAilmentEffectiveness($monster);
            $this->withLinkedItems($monster);
        });
    }

    private function withBodyPart(Monster $monster): void
    {
        MonsterBodyPartFactory::createMany(5, [
            'monster' => $monster,
        ]);
    }

    private function withAilmentEffectiveness(Monster $monster): void
    {
        foreach (StatusEffect::monstersAilmentsCases() as $case) {
            MonsterAilmentEffectivenessFactory::createOne([
                'ailment' => $case,
                'monster' => $monster,
            ]);
        }
    }

    private function withLinkedItems(Monster $monster): void
    {
        MonsterItemFactory::createMany(15, [
            'monster' => $monster,
        ]);
    }

    protected static function getClass(): string
    {
        return Monster::class;
    }
}
