<?php

namespace App\Synchronizer\Enum;

enum ItemSelector: string
{
    case LIST_DIV = 'div.flex.items-center';
    case LIST_A = 'div.flex.items-center a';
    case LIST_IMG = 'div.flex.items-center div';
    case DETAIL_TITLE_H1 = 'h1.font-display.text-3xl.tracking-tight.text-slate-900.dark\:text-white';
    case DETAIL_DESCRIPTION_P = 'header.mb-9.space-y-1 p:last-of-type';
}
