<?php

namespace App\Repository\Weapon;

use App\Entity\Equipment\Weapon\WeaponMaterial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeaponMaterial>
 */
class WeaponMaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeaponMaterial::class);
    }
}
