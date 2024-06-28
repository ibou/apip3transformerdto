<?php

namespace App\Synchronizer\Enum;

enum MonsterSelector: string
{
    case LIST_DIV = 'div.group.relative.p-4.border-r.border-b.border-gray-200';
    case LIST_A = 'div.group.relative.p-4.border-r.border-b.border-gray-200 a';
    case LIST_IMG = 'div.group.relative.p-4.border-r.border-b.border-gray-200 img';

    case DETAIL_NAME_H1 = 'h1';
    case DETAIL_DESCRIPTION_P = 'header.mb-9.space-y-1 p:last-of-type';

    case DETAIL_TABLE_TBODY = 'table.divide-y.divide-slate-100 tbody';
}
