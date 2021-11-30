<?php

declare(strict_types=1);

namespace Orm\OrmBundle\Command\Migration;

use Orm\Migration\DatabaseCreator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
{
    private DatabaseCreator $creator;

    public function __construct(DatabaseCreator $creator)
    {
        $this->creator = $creator;

        parent::__construct('orm:database:create');
    }

    protected function configure(): void
    {
        $this->setDescription('Create database schema');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->creator->create();

        return Command::SUCCESS;
    }
}
