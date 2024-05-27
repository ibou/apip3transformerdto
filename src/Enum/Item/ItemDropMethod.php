<?php

namespace App\Enum\Item;

use App\Contract\Labelized;
use App\Trait\Enum\FromLabelTrait;

enum ItemDropMethod: string implements Labelized
{
    use FromLabelTrait;

    case CARVES = 'carves';
    case TARGET_REWARDS = 'target_reward';
    case CAPTURE_REWARD = 'capture_reward';
    case BROKEN_PART_REWARDS = 'broken_part_rewards';
    case PALICO = 'palico';
    case DROPPED_MATERIALS = 'dropped_materials';

    public function label(): string
    {
        return match ($this) {
            self::CARVES => 'Carves',
            self::TARGET_REWARDS => 'Target Rewards',
            self::CAPTURE_REWARD => 'Capture Rewards',
            self::BROKEN_PART_REWARDS => 'Broken Part Rewards',
            self::PALICO => 'Items Obtained by Palico',
            self::DROPPED_MATERIALS => 'Dropped Materials'
        };
    }
}
