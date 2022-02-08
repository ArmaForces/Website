<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Cli;

use App\ModManagement\Infrastructure\Service\LegacyModListImport\ModListImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportModListsCommand extends Command
{
    public function __construct(
        private ModListImport $modListImport
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:import:modlists')
            ->setDescription('Imports mods')
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_REQUIRED,
                'Path to directory with CSV files',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getOption('path');

        $this->modListImport->importFromDirectory($path);

        return 0;
    }
}
