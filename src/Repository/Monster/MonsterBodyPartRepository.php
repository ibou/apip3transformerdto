<?php

namespace App\Repository\Monster;

use App\Entity\Monster\MonsterBodyPart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MonsterBodyPart>
 */
class MonsterBodyPartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonsterBodyPart::class);
    }
}
