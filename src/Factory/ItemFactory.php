<?php

namespace App\Factory;

use App\Entity\Item;
use App\Enum\Item\ItemType;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Item>
 */
final class ItemFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(255),
            'type' => self::faker()->randomElement(ItemType::cases()),
            'description' => self::faker()->text(),
            'imageUrl' => self::faker()->url(),
        ];
    }

    protected static function getClass(): string
    {
        return Item::class;
    }
}
