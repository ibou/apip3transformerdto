<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\ItemFactory;
use App\Factory\Monster\MonsterFactory;
use Zenstruck\Foundry\Story;

final class AppStory extends Story
{
    public function build(): void
    {
        ItemFactory::createMany(500);
        MonsterFactory::createMany(50);
    }
}
