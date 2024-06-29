<?php

namespace App\Entity\Equipment\Companion;

use App\Entity\Equipment\ArmorResistance;
use App\Repository\Equipment\Companion\CompanionArmorResistanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanionArmorResistanceRepository::class)]
class CompanionArmorResistance extends ArmorResistance
{
    #[ORM\ManyToOne(inversedBy: 'resistances')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?CompanionArmor $armor = null;

    public function getArmor(): ?CompanionArmor
    {
        return $this->armor;
    }

    public function setArmor(?CompanionArmor $armor): static
    {
        $this->armor = $armor;

        return $this;
    }
}
