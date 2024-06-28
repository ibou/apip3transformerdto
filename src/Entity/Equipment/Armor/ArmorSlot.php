<?php

namespace App\Entity\Equipment\Armor;

use App\Entity\Equipment\EquipmentSlot;
use App\Repository\Armor\ArmorSlotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArmorSlotRepository::class)]
class ArmorSlot extends EquipmentSlot
{
    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Armor $armor = null;

    public function getArmor(): ?Armor
    {
        return $this->armor;
    }

    public function setArmor(?Armor $armor): static
    {
        $this->armor = $armor;

        return $this;
    }
}
