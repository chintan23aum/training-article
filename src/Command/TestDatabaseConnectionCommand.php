<?php

namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'TestDatabaseConnectionCommand',
    description: 'Add a short description for your command',
)]
class TestDatabaseConnectionCommand extends Command
{
    private Connection $databaseConnection;

    public function __construct(Connection $databaseConnection)
    {
        parent::__construct();
        $this->databaseConnection = $databaseConnection;
    }

    protected function configure(): void
    {
        $this->setDescription('Check database connection');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->databaseConnection->connect();
            $output->writeln('Database connection successful!');
            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln('Error: ' . $exception->getMessage());
            return Command::FAILURE;
        }
    }
}
