<?php

namespace App\Entity\Equipment\Armor;

use App\Enum\StatusEffect;
use App\Repository\Equipment\Armor\ArmorResistanceRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArmorResistanceRepository::class)]
class ArmorResistance
{
    use IdTrait;

    #[ORM\Column(length: 255)]
    private ?StatusEffect $element = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $value = null;

    #[ORM\ManyToOne(inversedBy: 'resistances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Armor $armor = null;

    public function getElement(): ?StatusEffect
    {
        return $this->element;
    }

    public function setElement(?StatusEffect $element): ArmorResistance
    {
        $this->element = $element;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

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
