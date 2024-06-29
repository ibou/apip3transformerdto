<?php

namespace App\Enum\Companion;

use App\Contract\Labelized;

enum CompanionType: string implements Labelized
{
    case PALAMUTE = 'palamute';
    case PALICO = 'palico';

    public function label(): string
    {
        return match ($this) {
            self::PALAMUTE => 'Palamute',
            self::PALICO => 'Palico'
        };
    }

    public function plural(): string
    {
        return match ($this) {
            self::PALAMUTE => 'palamutes',
            self::PALICO => 'palicoes'
        };
    }
}
