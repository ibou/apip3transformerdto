<?php

namespace App\Factory\Skill;

use App\Entity\Skill\SkillLevel;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<SkillLevel>
 */
final class SkillLevelFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'effect' => self::faker()->text(255),
            'level' => self::faker()->numberBetween(1, 5),
        ];
    }

    protected static function getClass(): string
    {
        return SkillLevel::class;
    }
}
