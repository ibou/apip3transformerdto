<?php

namespace App\Synchronizer\Enum;

enum QuestSelector: string
{
    case LIST_A = 'div.overflow-hidden tbody a';
    case DETAIL_TITLE_H1 = 'h1.font-display.text-3xl';
    case DETAIL_DESCRIPTION_P = 'header.mb-9.space-y-1 p:last-of-type';
    case DETAIL_OBJECTIVE_HRP_MRP_FAILURE_CONDITIONS_DIV = 'dl.mt-5.grid.grid-cols-1 dd > div';

    case DETAIL_TABLE_TBODY = 'table.divide-y.divide-slate-100 tbody';

    case DETAIl_SIZES_AREAS_MONSTER_NAME_ANCHOR = 'div.basis-1\\/5 div:first-of-type > a';
    case DETAIL_SIZES_TBODY = 'div.basis-1\\/5 div:nth-of-type(2) tbody';
    case DETAIL_AREAS_TBODY = 'div.basis-1\\/5 div:nth-of-type(3) tbody';

    case DETAIL_ITEMS_TBODY = 'div.basis-1\\/2 tbody';
}
