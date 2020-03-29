<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport;

use App\Entity\ModList\ModList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Finder;

class ModListImport
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ModListCsvReader */
    protected $modListCsvReader;

    /** @var DtoToEntityConverter */
    protected $dtoToEntityConverter;

    public function __construct(
        EntityManagerInterface $entityManager,
        ModListCsvReader $modListCsvReader,
        DtoToEntityConverter $dtoToEntityConverter
    ) {
        $this->entityManager = $entityManager;
        $this->modListCsvReader = $modListCsvReader;
        $this->dtoToEntityConverter = $dtoToEntityConverter;
    }

    public function importFromDirectory(string $path, string $extension = '.csv'): void
    {
        $files = $this->findFilesToImport($path, $extension);
        foreach ($files as $file) {
            $filePath = $file->getPathname();
            $baseName = $file->getBasename($extension);

            $modList = new ModList($baseName);

            foreach ($this->modListCsvReader->readCsvRow($filePath) as $modCsvEntryDto) {
                $modEntity = $this->dtoToEntityConverter->convert($modCsvEntryDto);
                $modList->addMod($modEntity);
            }

            if (\count($modList->getMods()) > 0) {
                $this->entityManager->persist($modList);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @return \SplFileInfo[]
     */
    protected function findFilesToImport(string $path, string $extension): iterable
    {
        $finder = new Finder();
        $files = $finder->in($path)->files()->name("*{$extension}");

        return $files->getIterator();
    }
}
