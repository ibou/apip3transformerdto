<?php

namespace App\Synchronizer\Enum;

enum WeaponSelector: string
{
    case LIST_ITEM_TR = 'div.overflow-x-auto table tbody > tr';
    case LIST_ITEM_ANCHOR = 'td:nth-of-type(2) a';
    case LIST_ITEM_RARITY_SMALL = 'td:last-of-type > small';

    case DETAIL_H1 = 'h1';
    case DETAIL_IMG = 'div.min-w-0.max-w-2xl article div:nth-of-type(2) > div.relative img';
    case DETAIL_ATTACK_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(3)';
    case DETAIL_SLOTS_SPAN = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(2) div > span';
    case DETAIL_AILMENTS_SPAN = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(4) small > span';
    case DETAIL_SHARPNESS_SMALL = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(5) small';
    case DETAIL_AFFINITY_DEFENSE_BONUS_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(4) small > div';
}
