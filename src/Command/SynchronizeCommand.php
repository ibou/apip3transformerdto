<?php

namespace App\Command;

use App\Synchronizer\AbstractSynchronizer;
use App\Synchronizer\ArmorSynchronizer;
use App\Synchronizer\QuestSynchronizer;
use App\Synchronizer\SkillSynchronizer;
use App\Synchronizer\WeaponSynchronizer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

#[AsCommand(
    name: self::NAME,
    description: 'Synchronize database from Kiranico\'s website',
)]
class SynchronizeCommand extends Command
{
    private const string NAME = 'app:synchronize';

    public function __construct(
        private readonly LoggerInterface $logger,
        /** @var list<AbstractSynchronizer> $synchronizers */
        #[TaggedIterator('app.synchronizer')] private readonly iterable $synchronizers
    ) {
        parent::__construct(self::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            ini_set('memory_limit', '-1');
            $this->synchronize();
            ini_set('memory_limit', '128M');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->logger->critical($e->getTraceAsString());

            return Command::FAILURE;
        }
    }

    private function synchronize(): void
    {
        foreach ($this->synchronizers as $synchronizer) {
            $synchronizer->synchronize();
        }
    }
}
