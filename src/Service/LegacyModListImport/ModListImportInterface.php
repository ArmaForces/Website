<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport;

interface ModListImportInterface
{
    public function importFromDirectory(string $path, string $extension = '.csv'): void;
}
