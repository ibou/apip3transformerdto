<?php

namespace App\Enum\Quest;

use App\Contract\Labelized;
use App\Trait\Enum\FromLabelTrait;

enum QuestType: string implements Labelized
{
    use FromLabelTrait;

    case EVENT = 'event';
    case ANOMALY = 'anomaly';
    case FOLLOWER_COLLAB = 'follower_collab';
    case HUB_MASTER = 'hub_master';
    case HUB_HIGH = 'hub_high';
    case HUB_LOW = 'hub_low';
    case VILLAGE = 'village';
    case ARENA = 'arena';
    case TRAINING = 'training';

    public function label(): string
    {
        return match ($this) {
            self::EVENT => 'Event Quests',
            self::ANOMALY => 'Anomaly Quests',
            self::FOLLOWER_COLLAB => 'Follower Collab Quests',
            self::HUB_MASTER => 'Hub Quests: Master Rank',
            self::HUB_HIGH => 'Hub Quests: High Rank',
            self::HUB_LOW => 'Hub Quests: Low Rank',
            self::VILLAGE => 'Village Quests',
            self::ARENA => 'Arena Quests',
            self::TRAINING => 'Training Quests',
        };
    }

    public function kiranicoView(): string
    {
        return match ($this) {
            self::ANOMALY => 'mystery',
            self::FOLLOWER_COLLAB => 'follower',
            default => $this->value
        };
    }
}
