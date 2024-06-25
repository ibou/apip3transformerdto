<?php

namespace App\Enum\Equipment\Skill;

use App\Contract\Labelized;

enum SkillType: string implements Labelized
{
    case BASIC = 'basic';
    case RAMPAGE = 'rampage';

    public function label(): string
    {
        return match ($this) {
            self::BASIC => 'Basic',
            self::RAMPAGE => 'Rampage'
        };
    }
}
