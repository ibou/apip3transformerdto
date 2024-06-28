<?php

namespace App\Synchronizer\Enum;

enum ItemSelector: string
{
    case LIST_DIV = 'div.flex.items-center';
    case LIST_ANCHOR = 'div.flex.items-center a';
    case LIST_IMG = 'div.flex.items-center div img';
    case DETAIL_NAME_H1 = 'h1';
    case DETAIL_DESCRIPTION_P = 'header.mb-9.space-y-1 p:last-of-type';
}
