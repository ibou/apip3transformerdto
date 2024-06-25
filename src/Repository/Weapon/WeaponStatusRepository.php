<?php

namespace App\Repository\Weapon;

use App\Entity\Equipment\Weapon\WeaponStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeaponStatus>
 */
class WeaponStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeaponStatus::class);
    }
}
