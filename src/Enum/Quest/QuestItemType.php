<?php

namespace App\Enum\Quest;

use App\Contract\Labelized;
use App\Trait\Enum\FromLabelTrait;

enum QuestItemType: string implements Labelized
{
    use FromLabelTrait;

    case REWARD = 'reward';
    case SUPPLY_BOX = 'supply_box';

    public function label(): string
    {
        return match ($this) {
            self::REWARD => 'Reward',
            self::SUPPLY_BOX => 'Supply Box',
        };
    }
}
