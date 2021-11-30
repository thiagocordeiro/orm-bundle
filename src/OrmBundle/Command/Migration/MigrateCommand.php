<?php

declare(strict_types=1);

namespace Orm\OrmBundle\Command\Migration;

use Orm\Migration\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    private Migrator $migrator;

    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;

        parent::__construct('orm:migrations:migrate');
    }

    protected function configure(): void
    {
        $this->setDescription('Run database migration');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->writeln($output, "<info>Migrating database...</info>");
        $this->writeln($output, "");
        $migrations = iterator_to_array($this->migrator->migrate());

        if (false === empty($migrations)) {
            foreach ($migrations as $migration) {
                $this->writeln($output, "  <info>Migrated</info>: {$migration}");
            }
        }

        $this->writeln($output, sprintf("  <info>++</info> %s migrations executed", count($migrations)));
        $this->writeln($output, "");
        $this->writeln($output, "<info>Finished!</info>");

        return Command::SUCCESS;
    }

    private function writeln(OutputInterface $output, string $message): void
    {
        $output->write(sprintf('%s%s', $message, PHP_EOL));
    }
}
