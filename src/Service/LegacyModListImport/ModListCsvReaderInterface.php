<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport;

interface ModListCsvReaderInterface
{
    public function readCsvRow(string $path, string $delimiter = ';'): \Generator;
}
