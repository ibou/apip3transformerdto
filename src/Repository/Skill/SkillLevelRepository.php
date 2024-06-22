<?php

namespace App\Repository\Skill;

use App\Entity\Skill\SkillLevel;
use App\Enum\Skill\SkillType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SkillLevel>
 */
class SkillLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkillLevel::class);
    }

    public function findOneByNameTypeAndLevel(string $name, SkillType $type, int $level): ?SkillLevel
    {
        $qb = $this->createQueryBuilder('e');
        $qb->innerJoin('e.skill', 'skill');
        $qb->andWhere('skill.name = :name AND skill.type = :type');
        $qb->andWhere('e.level = :level');
        $qb->setParameters(['name' => $name, 'type' => $type, 'level' => $level]);
        $qb->setMaxResults(1);

        /** @var SkillLevel|null $entity */
        $entity = $qb->getQuery()->getOneOrNullResult();

        return $entity;
    }
}
