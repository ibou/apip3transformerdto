<?php

namespace App\Repository\Monster;

use App\Entity\Monster\MonsterItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MonsterItem>
 */
class MonsterItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonsterItem::class);
    }
}
