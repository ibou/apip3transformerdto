<?php

namespace App\Synchronizer\Enum;

enum SkillSelector: string
{
    case LIST_BASIC_SKILLS_ANCHOR = 'div.overflow-x-auto.text-gray-700 tbody tr td a';

    case DETAIL_NAME_H1 = 'h1.font-display.text-3xl.tracking-tight';
    case DETAIL_DESCRIPTION_P = 'header.mb-9.space-y-1 p:last-of-type';
    case DETAIL_BASIC_LEVELS_TR = 'body > div.relative.mx-auto.flex.max-w-8xl.justify-center.sm\:px-2.lg\:px-8.xl\:px-12 > div.min-w-0.max-w-2xl.flex-auto.px-4.py-16.lg\:max-w-none.lg\:pr-0.lg\:pl-8.xl\:px-16 > article > div > div:nth-child(3) > div > div > table tr';
}
