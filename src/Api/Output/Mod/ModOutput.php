<?php

declare(strict_types=1);

namespace App\Api\Output\Mod;

class ModOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public string $source,
        public string $type,
        public ?string $status,
        public ?int $itemId,
        public ?string $directory,
        public ?\DateTimeInterface $createdAt,
        public ?\DateTimeInterface $lastUpdatedAt,
    ) {
    }
}
