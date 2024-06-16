<?php

namespace App\Helper;

use Doctrine\DBAL\Logging\Middleware;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\NullLogger;

readonly class SynchronizerHelper
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function disableSQLLog(): void
    {
        $middlewares = new Middleware(new NullLogger());
        $this->em->getConnection()->getConfiguration()->setMiddlewares([$middlewares]);
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

    /**
     * @return string[]
     */
    public function transformMonsterAttributeHps(string $value): array
    {
        $values = [];
        foreach (\explode("\n", $value) as $line) {
            $value = \trim(\str_replace('HP', '', $line));
            $values[] = $value;
        }

        return $values;
    }

    public function getWeaponSlotQuantityFromImageSrc(string $src): ?int
    {
        return match (true) {
            \str_contains($src, 'deco0') => 0,
            \str_contains($src, 'deco1') => 1,
            \str_contains($src, 'deco2') => 2,
            \str_contains($src, 'deco3') => 3,
            \str_contains($src, 'deco4') => 4,
            default => null
        };
    }
}
