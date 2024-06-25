<?php

namespace App\Entity\Equipment\Armor;

use App\Entity\Equipment\EquipmentMaterial;
use App\Repository\Armor\ArmorMaterialRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArmorMaterialRepository::class)]
class ArmorMaterial extends EquipmentMaterial
{
    #[ORM\ManyToOne(inversedBy: 'materials')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Armor $armor = null;

    public function getArmor(): ?Armor
    {
        return $this->armor;
    }

    public function setArmor(?Armor $armor): ArmorMaterial
    {
        $this->armor = $armor;

        return $this;
    }
}
