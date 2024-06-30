<?php

namespace App\Factory\Kitchen;

use App\Entity\Kitchen\Dango;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Dango>
 */
final class DangoFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(255),
            'effects' => [
                self::faker()->text(255),
                self::faker()->text(255),
                self::faker()->text(255),
            ],
        ];
    }

    protected static function getClass(): string
    {
        return Dango::class;
    }
}
