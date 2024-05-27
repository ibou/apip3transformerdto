<?php

namespace App\Enum\Quest;

use App\Contract\Labelized;
use App\Trait\Enum\FromLabelTrait;

enum QuestRank: string implements Labelized
{
    use FromLabelTrait;

    case LOW = 'low';
    case HIGH = 'high';
    case MASTER = 'master';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low Rank',
            self::HIGH => 'High Rank',
            self::MASTER => 'Master Rank'
        };
    }
}
