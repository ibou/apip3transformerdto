<?php

namespace App\Enum;

enum Ailment: string
{
    case POISON = 'poison';
    case PARALYSIS = 'paralysis';
    case SLEEP = 'sleep';
    case STUN = 'stun';
    case BLAST = 'blast';
    case EXHAUST = 'exhaust';

    // FIXME enum ELEMENT
    case FIREBLIGHT = 'fireblight';
    case WATERBLIGHT = 'waterblight';
    case ICEBLIGHT = 'iceblight';
    case THUNDERBLIGHT = 'thunderblight';
    case DRAGONBLIGHT = 'dragonblight';

    public static function tryFromValue(int $value): ?self
    {
        return match ($value) {
            1 => self::FIREBLIGHT,
            2 => self::WATERBLIGHT,
            3 => self::THUNDERBLIGHT,
            4 => self::ICEBLIGHT,
            5 => self::DRAGONBLIGHT,
            6 => self::POISON,
            7 => self::SLEEP,
            8 => self::PARALYSIS,
            9 => self::BLAST,
            default => null
        };
    }
}
