<?php

namespace App\Repository\Quest;

use App\Entity\Quest\QuestItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestItem>
 */
class QuestItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestItem::class);
    }
}
