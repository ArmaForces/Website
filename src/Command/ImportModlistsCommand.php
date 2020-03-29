<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\LegacyModListImport\ModListImport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportModlistsCommand extends Command
{
    public const DEFAULT_IMPORT_DIRECTORY = __DIR__.'/../../var/import';

    /** @var string */
    protected static $defaultName = 'app:import:modlists';

    /** @var ModListImport */
    protected $modListImport;

    /**
     * @{@inheritdoc}
     */
    public function __construct(ModListImport $modListImport)
    {
        parent::__construct();

        $this->modListImport = $modListImport;
    }

    /**
     * @{@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Imports mods')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'Path to directory with CSV files', self::DEFAULT_IMPORT_DIRECTORY)
        ;
    }

    /**
     * @{@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = $input->getOption('path');

        $this->modListImport->importFromDirectory($path);

        return 0;
    }
}
