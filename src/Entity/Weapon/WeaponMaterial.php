<?php

namespace App\Entity\Weapon;

use App\Entity\Item;
use App\Enum\Weapon\WeaponMaterialType;
use App\Repository\Weapon\WeaponMaterialRepository;
use App\Trait\IdTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeaponMaterialRepository::class)]
class WeaponMaterial
{
    use IdTrait;

    #[ORM\Column(length: 255)]
    private ?WeaponMaterialType $type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Item $item = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'materials')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Weapon $weapon = null;

    public function getType(): ?WeaponMaterialType
    {
        return $this->type;
    }

    public function setType(WeaponMaterialType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

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
