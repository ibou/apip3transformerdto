<?php

namespace App\Repository\Equipment\Companion;

use App\Entity\Equipment\Companion\CompanionWeaponStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanionWeaponStatus>
 */
class CompanionWeaponStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanionWeaponStatus::class);
    }
}
