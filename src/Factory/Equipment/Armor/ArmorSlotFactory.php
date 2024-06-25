<?php

namespace App\Factory\Equipment\Armor;

use App\Entity\Equipment\Armor\ArmorSlot;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<ArmorSlot>
 */
final class ArmorSlotFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'quantity' => self::faker()->numberBetween(1, 10),
            'type' => self::faker()->text(255),
        ];
    }

    protected static function getClass(): string
    {
        return ArmorSlot::class;
    }
}
