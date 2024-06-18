<?php

namespace App\Repository\Weapon;

use App\Entity\Weapon\WeaponExtra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeaponExtra>
 */
class WeaponExtraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeaponExtra::class);
    }
}
