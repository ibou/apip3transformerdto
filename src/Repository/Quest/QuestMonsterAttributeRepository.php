<?php

namespace App\Repository\Quest;

use App\Entity\Quest\QuestMonsterAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestMonsterAttribute>
 */
class QuestMonsterAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestMonsterAttribute::class);
    }
}
