<?php

declare(strict_types=1);

namespace App\Mods\Api\Output\ModList;

use App\Mods\Api\Output\Dlc\DlcOutput;
use App\Mods\Api\Output\Mod\ModOutput;

class ModListDetailsOutput extends ModListOutput
{
    /**
     * @param ModOutput[] $mods
     * @param DlcOutput[] $dlcs
     */
    public function __construct(
        string $id,
        string $name,
        ?bool $active,
        \DateTimeInterface $createdAt,
        ?\DateTimeInterface $lastUpdatedAt,
        ?bool $approved,
        public array $mods,
        public array $dlcs,
        public ?string $url
    ) {
        parent::__construct(
            $id,
            $name,
            $active,
            $createdAt,
            $lastUpdatedAt,
            $approved,
        );
    }
}
