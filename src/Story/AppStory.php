<?php

declare(strict_types=1);

namespace App\Story;

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
        QuestFactory::createMany(10);
    }
}
