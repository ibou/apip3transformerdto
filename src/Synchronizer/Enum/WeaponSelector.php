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
    case DETAIL_MATERIALS_DIV = 'div.basis-1\\/2';
    case DETAIL_SLOTS_SPAN = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(2) div > span';
    case DETAIL_AILMENTS_SPAN = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(4) small > span';
    case DETAIL_SHARPNESS_SMALL = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(5) small';
    case DETAIL_BOW_SHOTS_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(5) small > div';
    case DETAIL_AFFINITY_DEFENSE_BONUS_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(4) small > div';
    case DETAIL_HUNTING_HORN_STATUSES = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(6) small';
    case DETAIL_GUNLANCE_TYPE_OR_BOW_COATINGS_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(6) small > div';
    case DETAIL_SWITCH_AXE_OR_CHARGE_BLADE_INSECT_GLAIVE_EXTRA_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(6) small > div > div';
    case DETAIL_BOWGUN_EXTRA_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(5) small tr td:first-of-type div';
    case DETAIL_BOWGUN_AMMO_EXTRA_TR = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(5) small tr td:not(first-of-type) table tr';
    case DETAIL_SKILLS_DIV = 'div.min-w-0.max-w-2xl table tbody tr td:nth-of-type(7) small > div';
}
