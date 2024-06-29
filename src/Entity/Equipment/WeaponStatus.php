<?php

namespace App\Entity\Equipment;

use App\Enum\StatusEffect;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\MappedSuperclass]
abstract class WeaponStatus
{
    use IdTrait;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255, enumType: StatusEffect::class)]
    protected ?StatusEffect $status = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    protected ?int $value = null;

    public function getStatus(): ?StatusEffect
    {
        return $this->status;
    }

    public function setStatus(?StatusEffect $status): WeaponStatus
    {
        $this->status = $status;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): WeaponStatus
    {
        $this->value = $value;

        return $this;
    }
}
