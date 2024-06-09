<?php

namespace App\Synchronizer;

use App\Helper\SynchronizerHelper;
use App\Synchronizer\Service\Cache;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

#[AutoconfigureTag('app.synchronizer')]
abstract class AbstractSynchronizer
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $em,
        private readonly SynchronizerHelper $helper,
        private readonly Cache $cache,
        private readonly string $kiranicoUrl
    ) {
    }

    abstract public function synchronize(): void;

    protected function em(): EntityManagerInterface
    {
        return $this->em;
    }

    protected function logger(): LoggerInterface
    {
        return $this->logger;
    }

    protected function helper(): SynchronizerHelper
    {
        return $this->helper;
    }

    protected function cache(): Cache
    {
        return $this->cache;
    }

    protected function getKiranicoUrl(): string
    {
        return $this->kiranicoUrl;
    }

    protected function flushAndClear(): void
    {
        $this->em->flush();

        $this->em->clear();
        $this->cache()->clear();

        gc_collect_cycles();
    }

    protected function ping(): void
    {
        try {
            if (!Utils::ping($this->getKiranicoUrl())) {
                throw new ServiceUnavailableHttpException();
            }
        } catch (\Throwable $e) {
            $this->logger->critical(\sprintf('Ping failed: %s, message: %s',
                $this->getKiranicoUrl(), $e->getMessage()));

            throw $e;
        }
    }
}
