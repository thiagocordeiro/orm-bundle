<?php

declare(strict_types=1);

namespace Orm\OrmBundle\Command\Migration;

use Orm\Migration\Creator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    private Creator $creator;

    public function __construct(Creator $creator)
    {
        $this->creator = $creator;

        parent::__construct('orm:migrations:create');
    }

    protected function configure(): void
    {
        $this->setDescription('Create a migration file');
        $this->addArgument('name', InputArgument::REQUIRED, 'The migration name, ex: create_foo_bar_table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        assert(is_string($name));

        $this->creator->create($name);

        return Command::SUCCESS;
    }
}
