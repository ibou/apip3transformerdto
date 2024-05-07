<?php

namespace App\Enum\Item;

use App\Contract\Labelized;

enum ItemType: string implements Labelized
{
    case CONSUME = 'consume';
    case MATERIAL = 'material';
    case SCRAP = 'scrap';
    case AMMO = 'ammo';
    case ACCOUNT = 'account';
    case ANTIQUE = 'antique';

    public function label(): string
    {
        return match ($this) {
            self::CONSUME => 'Healing / Support',
            self::MATERIAL => 'Material',
            self::SCRAP => 'Scrap',
            self::AMMO => 'Ammo',
            self::ACCOUNT => 'Delivery',
            self::ANTIQUE => 'Room Customization',
        };
    }
}
