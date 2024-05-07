<?php

namespace App\Enum\Item;

enum ItemSelector: string
{
    case ITEM_LIST_DIV = 'div.flex.items-center.border-r.border-b';
    case ITEM_LIST_IMG = 'div.flex.items-center img';
    case ITEM_DETAIL_TITLE_H1 = 'h1.font-display.text-3xl.tracking-tight.text-slate-900.dark\:text-white';
    case ITEM_DETAIL_DESCRIPTION_P = 'header.mb-9.space-y-1 p:last-of-type';
}
