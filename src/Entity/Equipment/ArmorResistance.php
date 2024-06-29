<?php

namespace App\Entity\Equipment;

use App\Enum\StatusEffect;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class ArmorResistance
{
    use IdTrait;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    protected ?StatusEffect $element = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::SMALLINT)]
    protected ?int $value = null;

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

    public function setValue(?int $value): ArmorResistance
    {
        $this->value = $value;

        return $this;
    }
}
