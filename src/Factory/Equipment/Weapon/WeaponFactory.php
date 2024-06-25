<?php

namespace App\Factory\Equipment\Weapon;

use App\Entity\Equipment\Weapon\Weapon;
use App\Enum\Equipment\EquipmentMaterialType;
use App\Enum\Equipment\EquipmentSlotType;
use App\Enum\Equipment\Weapon\WeaponType;
use App\Factory\Equipment\Skill\SkillLevelFactory;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Weapon>
 */
final class WeaponFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'attack' => self::faker()->numberBetween(1, 350),
            'defenseBonus' => self::faker()->numberBetween(1, 25),
            'name' => self::faker()->unique()->word(),
            'rarity' => self::faker()->numberBetween(1, 10),
            'affinity' => self::faker()->numberBetween(-20, 20),
            'sharpness' => self::faker()->randomHtml(),
            'imagesUrls' => [self::faker()->imageUrl(), self::faker()->imageUrl()],
            'type' => self::faker()->randomElement(WeaponType::cases()),
            'addSkill' => SkillLevelFactory::repository()->random(),
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (Weapon $weapon): void {
            $this->withSlots($weapon);
            $this->withMaterials($weapon);
            $this->withStatuses($weapon);
            $this->withSkills($weapon);
            $this->withExtra($weapon);
        });
    }

    private function withSlots(Weapon $weapon): void
    {
        WeaponSlotFactory::createMany(self::faker()->numberBetween(1, 3), [
            'weapon' => $weapon,
            'type' => EquipmentSlotType::BASIC,
        ]);

        WeaponSlotFactory::createMany(self::faker()->numberBetween(1, 3), [
            'weapon' => $weapon,
            'type' => EquipmentSlotType::RAMPAGE,
        ]);
    }

    private function withMaterials(Weapon $weapon): void
    {
        WeaponMaterialFactory::createMany(self::faker()->numberBetween(1, 5), [
            'weapon' => $weapon,
            'type' => EquipmentMaterialType::FORGING,
        ]);

        WeaponMaterialFactory::createMany(self::faker()->numberBetween(1, 5), [
            'weapon' => $weapon,
            'type' => EquipmentMaterialType::UPGRADE,
        ]);
    }

    private function withStatuses(Weapon $weapon): void
    {
        WeaponStatusFactory::createMany(self::faker()->numberBetween(1, 2), [
            'weapon' => $weapon,
        ]);
    }

    private function withSkills(Weapon $weapon): void
    {
        $skills = SkillLevelFactory::repository()->randomRange(1, 5);
        foreach ($skills as $skill) {
            $weapon->addSkill($skill->object());
        }
    }

    private function withExtra(Weapon $weapon): void
    {
        WeaponExtraFactory::createMany(self::faker()->numberBetween(1, 2), [
            'weapon' => $weapon,
        ]);
    }

    protected static function getClass(): string
    {
        return Weapon::class;
    }
}
