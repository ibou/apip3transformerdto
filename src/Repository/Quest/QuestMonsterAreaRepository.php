<?php

namespace App\Repository\Quest;

use App\Entity\Quest\QuestMonsterArea;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestMonsterArea>
 */
class QuestMonsterAreaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestMonsterArea::class);
    }
}
