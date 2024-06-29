<?php

namespace App\Entity\Equipment\Armor;

use App\Entity\Equipment\EquipmentMaterial;
use App\Repository\Equipment\Armor\ArmorMaterialRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArmorMaterialRepository::class)]
class ArmorMaterial extends EquipmentMaterial
{
    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'materials')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
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
