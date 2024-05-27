<?php

namespace App\Command;

use App\Synchronizer\ItemSynchronizer;
use App\Synchronizer\MonsterSynchronizer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: self::NAME,
    description: 'Synchronize database from Kiranico\'s website',
)]
class SynchronizeCommand extends Command
{
    private const string NAME = 'app:synchronize';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ItemSynchronizer $itemSynchronizer,
        private readonly MonsterSynchronizer $monsterSynchronizer
    ) {
        parent::__construct(self::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->itemSynchronizer->synchronize();
            $this->monsterSynchronizer->synchronize();

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->critical($e->getMessage());
            $this->logger->critical($e->getTraceAsString());

            return Command::FAILURE;
        }
    }
}
