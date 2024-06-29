<?php

namespace App\Entity\Equipment\Weapon;

use App\Entity\Equipment\EquipmentMaterial;
use App\Repository\Equipment\Weapon\WeaponMaterialRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeaponMaterialRepository::class)]
class WeaponMaterial extends EquipmentMaterial
{
    #[ORM\ManyToOne(inversedBy: 'materials')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Weapon $weapon = null;

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
