<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\SkeletonFactory;
use Zenstruck\Foundry\Story;

final class AppStory extends Story
{
    public function build(): void
    {
        SkeletonFactory::new()
            ->create(['name' => 'Hello World !']);
    }
}
