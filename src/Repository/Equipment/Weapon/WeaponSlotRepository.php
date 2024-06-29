<?php

namespace App\Repository\Equipment\Weapon;

use App\Entity\Equipment\Weapon\WeaponSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeaponSlot>
 */
class WeaponSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeaponSlot::class);
    }
}
