<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\Equipment\Armor\ArmorFactory;
use App\Factory\Equipment\Companion\CompanionArmorFactory;
use App\Factory\Equipment\Companion\CompanionWeaponFactory;
use App\Factory\Equipment\Skill\SkillFactory;
use App\Factory\Equipment\Weapon\WeaponFactory;
use App\Factory\ItemFactory;
use App\Factory\Monster\MonsterFactory;
use App\Factory\Quest\QuestFactory;
use Zenstruck\Foundry\Story;

final class AppStory extends Story
{
    public function build(): void
    {
        ItemFactory::createMany(100);

        MonsterFactory::createMany(25);
        QuestFactory::createMany(50);

        SkillFactory::createMany(50);
        WeaponFactory::createMany(50);
        ArmorFactory::createMany(50);

        CompanionArmorFactory::createMany(50);
        CompanionWeaponFactory::createMany(50);
    }
}
