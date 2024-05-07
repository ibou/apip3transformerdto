<?php

namespace App\Helper;

use Doctrine\ORM\EntityManagerInterface;

readonly class SynchronizerHelper
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function disableSQLLog(): void
    {
        $this->em->getConnection()->getConfiguration()->setMiddlewares([]);
    }

    public function cleanEntitiesData(string ...$fqcns): void
    {
        foreach ($fqcns as $fqcn) {
            $this->cleanEntityData($fqcn);
        }
    }

    public function cleanEntityData(string $fqcn): void
    {
        $this->em->createQueryBuilder()
            ->delete($fqcn, 'e')
            ->getQuery()
            ->execute();
    }
}
