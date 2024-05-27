<?php

namespace App\Repository\Monster;

use App\Entity\Monster\MonsterBodyPartWeakness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MonsterBodyPartWeakness>
 */
class MonsterBodyPartWeaknessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonsterBodyPartWeakness::class);
    }
}
