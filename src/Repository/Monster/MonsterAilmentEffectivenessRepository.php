<?php

namespace App\Repository\Monster;

use App\Entity\Monster\MonsterAilmentEffectiveness;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MonsterAilmentEffectiveness>
 */
class MonsterAilmentEffectivenessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonsterAilmentEffectiveness::class);
    }
}
