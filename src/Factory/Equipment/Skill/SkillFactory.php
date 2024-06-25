<?php

namespace App\Factory\Equipment\Skill;

use App\Entity\Equipment\Skill\Skill;
use App\Enum\Equipment\Skill\SkillType;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Skill>
 */
final class SkillFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'description' => self::faker()->text(),
            'name' => self::faker()->text(255),
            'type' => self::faker()->randomElement(SkillType::cases()),
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (Skill $skill): void {
            $this->withLevels($skill);
        });
    }

    private function withLevels(Skill $skill): void
    {
        SkillLevelFactory::createMany(self::faker()->numberBetween(1, 5), [
            'skill' => $skill,
        ]);
    }

    protected static function getClass(): string
    {
        return Skill::class;
    }
}
