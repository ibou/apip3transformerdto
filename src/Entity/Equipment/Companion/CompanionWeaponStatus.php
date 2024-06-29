<?php

namespace App\Entity\Equipment\Companion;

use App\Entity\Equipment\WeaponStatus;
use App\Repository\Equipment\Companion\CompanionWeaponStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanionWeaponStatusRepository::class)]
class CompanionWeaponStatus extends WeaponStatus
{
    #[ORM\ManyToOne(inversedBy: 'statuses')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?CompanionWeapon $weapon = null;

    public function getWeapon(): ?CompanionWeapon
    {
        return $this->weapon;
    }

    public function setWeapon(?CompanionWeapon $weapon): static
    {
        $this->weapon = $weapon;

        return $this;
    }
}
