<?php

namespace App\Entity\Weapon;

use App\Enum\Ailment;
use App\Repository\Weapon\WeaponAilmentRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeaponAilmentRepository::class)]
class WeaponAilment
{
    use IdTrait;

    #[ORM\Column(length: 255, enumType: Ailment::class)]
    private ?Ailment $ailment = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $value = null;

    #[ORM\ManyToOne(inversedBy: 'ailments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Weapon $weapon = null;

    public function getAilment(): ?Ailment
    {
        return $this->ailment;
    }

    public function setAilment(Ailment $ailment): static
    {
        $this->ailment = $ailment;

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

    public function getWeapon(): ?Weapon
    {
        return $this->weapon;
    }

    public function setWeapon(?Weapon $weapon): static
    {
        $this->weapon = $weapon;

        return $this;
    }
}
