<?php

namespace App\Factory\Equipment\Companion;

use App\Entity\Equipment\Companion\CompanionWeapon;
use App\Enum\Companion\CompanionType;
use App\Enum\Companion\CompanionWeaponType;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<CompanionWeapon>
 */
final class CompanionWeaponFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'companion' => self::faker()->randomElement(CompanionType::cases()),
            'defenseBonus' => self::faker()->numberBetween(1, 32767),
            'meleeAffinity' => self::faker()->numberBetween(1, 32767),
            'meleeAttack' => self::faker()->numberBetween(1, 32767),
            'name' => self::faker()->text(255),
            'rangedAffinity' => self::faker()->numberBetween(1, 32767),
            'rangedAttack' => self::faker()->numberBetween(1, 32767),
            'type' => self::faker()->randomElement(CompanionWeaponType::cases()),
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (CompanionWeapon $weapon): void {
            $this->withStatuses($weapon);
        });
    }

    private function withStatuses(CompanionWeapon $weapon): void
    {
        CompanionWeaponStatusFactory::createOne([
            'weapon' => $weapon,
        ]);
    }

    protected static function getClass(): string
    {
        return CompanionWeapon::class;
    }
}
