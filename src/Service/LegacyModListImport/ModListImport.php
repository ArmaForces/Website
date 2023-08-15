<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport;

use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\SteamWorkshopMod;
use App\Entity\ModList\ModList;
use App\Repository\Mod\DirectoryModRepository;
use App\Repository\Mod\SteamWorkshopModRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Finder\Finder;

class ModListImport implements ModListImportInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ModListCsvReaderInterface $modListCsvReader,
        private DtoToEntityConverterInterface $dtoToEntityConverter,
        private SteamWorkshopModRepository $steamWorkshopModRepository,
        private DirectoryModRepository $directoryModRepository
    ) {
    }

    public function importFromDirectory(string $path, string $extension = '.csv'): void
    {
        $files = $this->findFilesToImport($path, $extension);
        foreach ($files as $file) {
            $filePath = $file->getPathname();
            $baseName = $file->getBasename($extension);

            $mods = [];
            foreach ($this->modListCsvReader->readCsvRow($filePath) as $modCsvEntryDto) {
                $modEntity = $this->dtoToEntityConverter->convert($modCsvEntryDto);

                $existingMod = null;
                if ($modEntity instanceof SteamWorkshopMod) {
                    $existingMod = $this->steamWorkshopModRepository->findOneByItemId($modEntity->getItemId());
                } elseif ($modEntity instanceof DirectoryMod) {
                    $existingMod = $this->directoryModRepository->findOneByDirectory($modEntity->getDirectory());
                }

                $mods[] = $existingMod ?? $modEntity;
            }

            $modList = new ModList(
                Uuid::uuid4(),
                $baseName,
                null,
                $mods,
                [],
                [],
                null,
                true,
                false
            );

            if (\count($modList->getMods()) > 0) {
                $this->entityManager->persist($modList);
            }

            $this->entityManager->flush();
        }
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
