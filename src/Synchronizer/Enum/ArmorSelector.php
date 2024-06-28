<?php

namespace App\Synchronizer\Enum;

enum ArmorSelector: string
{
    case LIST_ITEM_ANCHOR = 'td:nth-of-type(3) a';

    case DETAIL_H1 = 'h1';
    case DETAIL_DESCRIPTION_P = 'header.mb-9.space-y-1 p:last-of-type';
    case DETAIL_IMG = 'div.min-w-0.max-w-2xl article div:nth-of-type(2) > div.relative img';
    case DETAIL_SLOTS_IMG = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(1) img';
    case DETAIL_DEFENSE_TD = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(1)';
    case DETAIL_RESISTANCES_SPAN = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(%d) > span';
    case DETAIL_SKILLS_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(8) div';
}
