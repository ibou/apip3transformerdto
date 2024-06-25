<?php

namespace App\Enum\Equipment;

use App\Contract\Labelized;
use App\Trait\Enum\FromLabelTrait;

enum EquipmentMaterialType: string implements Labelized
{
    use FromLabelTrait;

    case FORGING = 'forging';
    case UPGRADE = 'upgrade';

    public function label(): string
    {
        return match ($this) {
            self::FORGING => 'Forging Materials',
            self::UPGRADE => 'Upgrade Materials'
        };
    }
}
