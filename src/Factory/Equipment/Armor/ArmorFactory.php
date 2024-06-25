<?php

namespace App\Factory\Equipment\Armor;

use App\Entity\Equipment\Armor\Armor;
use App\Enum\Equipment\EquipmentMaterialType;
use App\Enum\Equipment\EquipmentSlotType;
use App\Enum\StatusEffect;
use App\Factory\Equipment\Skill\SkillLevelFactory;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Armor>
 */
final class ArmorFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'defense' => self::faker()->numberBetween(1, 100),
            'description' => self::faker()->text(),
            'imagesUrls' => [self::faker()->imageUrl(), self::faker()->imageUrl()],
            'name' => self::faker()->text(255),
            'rarity' => self::faker()->numberBetween(1, 10),
            'addSkill' => SkillLevelFactory::repository()->random(),
        ];
    }

    protected function initialize(): self
    {
        return $this->afterPersist(function (Armor $armor): void {
            $this->withSlots($armor);
            $this->withMaterials($armor);
            $this->withElements($armor);
        });
    }

    private function withSlots(Armor $armor): void
    {
        ArmorSlotFactory::createMany(self::faker()->numberBetween(1, 3), [
            'armor' => $armor,
            'type' => EquipmentSlotType::BASIC,
        ]);
    }

    private function withMaterials(Armor $armor): void
    {
        ArmorMaterialFactory::createMany(self::faker()->numberBetween(1, 5), [
            'armor' => $armor,
            'type' => EquipmentMaterialType::FORGING,
        ]);

        ArmorMaterialFactory::createMany(self::faker()->numberBetween(1, 5), [
            'armor' => $armor,
            'type' => EquipmentMaterialType::UPGRADE,
        ]);
    }

    private function withElements(Armor $armor): void
    {
        foreach (StatusEffect::elementsBlightsCases() as $element) {
            ArmorResistanceFactory::createOne([
                'element' => $element,
                'armor' => $armor,
            ]);
        }
    }

    protected static function getClass(): string
    {
        return Armor::class;
    }
}
