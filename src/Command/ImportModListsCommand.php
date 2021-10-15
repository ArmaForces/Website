<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\LegacyModListImport\ModListImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportModListsCommand extends Command
{
    public const DEFAULT_IMPORT_DIRECTORY = __DIR__.'/../../var/import';

    public function __construct(
        private ModListImport $modListImport
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:import:modlists')
            ->setDescription('Imports mods')
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Path to directory with CSV files',
                self::DEFAULT_IMPORT_DIRECTORY
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getOption('path');

        $this->modListImport->importFromDirectory($path);

        return 0;
    }
}
