<?php

namespace App\Entity\Equipment\Weapon;

use App\Repository\Equipment\Weapon\WeaponStatusRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WeaponStatusRepository::class)]
class WeaponStatus extends \App\Entity\Equipment\WeaponStatus
{
    #[Assert\NotNull]
    #[ORM\ManyToOne(inversedBy: 'statuses')]
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
