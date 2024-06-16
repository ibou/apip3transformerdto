<?php

namespace App\Entity\Weapon;

use App\Enum\Weapon\WeaponSlotType;
use App\Repository\Weapon\WeaponSlotRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeaponSlotRepository::class)]
class WeaponSlot
{
    use IdTrait;

    #[ORM\Column(length: 255)]
    private ?WeaponSlotType $type = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'slots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Weapon $weapon = null;

    public function getType(): ?WeaponSlotType
    {
        return $this->type;
    }

    public function setType(WeaponSlotType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

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
