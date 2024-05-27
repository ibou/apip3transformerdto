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
    case FIREBLIGHT = 'fireblight';
    case WATERBLIGHT = 'waterblight';
    case ICEBLIGHT = 'iceblight';
    case THUNDERBLIGHT = 'thunderblight';
}
