<?php

declare(strict_types=1);

namespace App\Api\Output\ModList;

use App\Api\Output\Dlc\DlcOutput;
use App\Api\Output\Mod\ModOutput;

class ModListDetailsOutput extends ModListOutput
{
    /**
     * @param ModOutput[] $mods
     * @param DlcOutput[] $dlcs
     */
    public function __construct(
        string $id,
        string $name,
        bool $active,
        bool $approved,
        \DateTimeInterface $createdAt,
        ?\DateTimeInterface $lastUpdatedAt,
        public array $mods,
        public array $dlcs
    ) {
        parent::__construct(
            $id,
            $name,
            $active,
            $approved,
            $createdAt,
            $lastUpdatedAt
        );
    }
}
