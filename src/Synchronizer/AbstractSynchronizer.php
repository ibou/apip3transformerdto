<?php

namespace App\Synchronizer;

use App\Helper\SynchronizerHelper;
use App\Synchronizer\Service\Cache;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AutoconfigureTag('app.synchronizer')]
abstract class AbstractSynchronizer
{
    private ?ProgressBar $progressBar = null;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly EntityManagerInterface $em,
        private readonly SynchronizerHelper $helper,
        private readonly Cache $cache,
        private readonly ValidatorInterface $validator,
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

    protected function output(): OutputInterface
    {
        return new ConsoleOutput();
    }

    protected function validator(): ValidatorInterface
    {
        return $this->validator;
    }

    protected function startProgressBar(int $count, string $message): void
    {
        $this->output()->writeln($message);

        $this->progressBar = new ProgressBar($this->output(), $count);
        $this->progressBar->setFormat('debug');
        $this->progressBar->start();
    }

    protected function advanceProgressBar(): void
    {
        $this->progressBar?->advance();
    }

    protected function finishProgressBar(): void
    {
        $this->progressBar?->finish();
        $this->output()->writeln('');
    }

    protected function kiranicoUrl(): string
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
            if (!Utils::ping($this->kiranicoUrl())) {
                throw new ServiceUnavailableHttpException();
            }
        } catch (\Throwable $e) {
            $this->logger->critical(\sprintf('Ping failed: %s, message: %s',
                $this->kiranicoUrl(), $e->getMessage()));

            throw $e;
        }
    }

    abstract public static function getDefaultPriority(): int;
}
