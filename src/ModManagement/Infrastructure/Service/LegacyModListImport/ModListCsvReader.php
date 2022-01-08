<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Service\LegacyModListImport;

use App\ModManagement\Infrastructure\Service\LegacyModListImport\Dto\ModCsvEntryDto;
use League\Csv\Reader;

class ModListCsvReader
{
    protected const COLUMN_INDEX_ID = 0;
    protected const COLUMN_INDEX_NAME = 1;
    protected const COLUMN_INDEX_IS_SERVER_SIDE = 2;
    protected const COLUMN_INDEX_IS_OPTIONAL = 3;
    protected const COLUMN_INDEX_IS_MAP = 4;

    public function readCsvRow(string $path, string $delimiter = ';'): \Generator
    {
        $reader = Reader::createFromPath($path);
        $reader->setDelimiter($delimiter);

        /** @var array[] $rows */
        $rows = $reader->getRecords();

        foreach ($rows as $row) {
            if (!$this->isRowValid($row)) {
                continue;
            }

            yield new ModCsvEntryDto(
                $row[self::COLUMN_INDEX_ID],
                $row[self::COLUMN_INDEX_NAME],
                $row[self::COLUMN_INDEX_IS_SERVER_SIDE],
                $row[self::COLUMN_INDEX_IS_OPTIONAL],
                $row[self::COLUMN_INDEX_IS_MAP]
            );
        }
    }

    /**
     * @param string[] $row
     */
    protected function isRowValid(array $row): bool
    {
        $id = $row[self::COLUMN_INDEX_ID];

        return 'local' === strtolower($id) || is_numeric($id);
    }
}
