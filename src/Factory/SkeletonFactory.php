<?php

namespace App\Factory;

use App\Entity\Skeleton;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Skeleton>
 */
final class SkeletonFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->text(255),
        ];
    }

    protected static function getClass(): string
    {
        return Skeleton::class;
    }
}
