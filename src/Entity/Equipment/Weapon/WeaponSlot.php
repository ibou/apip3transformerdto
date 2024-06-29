<?php

namespace App\Entity\Equipment\Weapon;

use App\Entity\Equipment\EquipmentSlot;
use App\Repository\Equipment\Weapon\WeaponSlotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeaponSlotRepository::class)]
class WeaponSlot extends EquipmentSlot
{
    #[ORM\ManyToOne(inversedBy: 'slots')]
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
