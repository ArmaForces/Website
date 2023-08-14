<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\LegacyModListImport\ModListImportInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportModListsCommand extends Command
{
    public function __construct(
        private ModListImportInterface $modListImport
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('app:import:modlists')
            ->setDescription('Imports mods')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Path to directory with CSV files',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getArgument('path');

        $this->modListImport->importFromDirectory($path);

        return 0;
    }
}
