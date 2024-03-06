<?php

declare(strict_types=1);

namespace App\Api\Output\ModList;

class ModListOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $active,
        public bool $approved,
        public \DateTimeInterface $createdAt,
        public ?\DateTimeInterface $lastUpdatedAt,
    ) {
    }
}
