<?php

namespace App\Entity\Equipment\Armor;

use App\Repository\Equipment\Armor\ArmorResistanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArmorResistanceRepository::class)]
class ArmorResistance extends \App\Entity\Equipment\ArmorResistance
{
    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'resistances')]
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
