<?php

declare(strict_types=1);

namespace App\Api\Output\Dlc;

class DlcOutput
{
    public function __construct(
        public string $id,
        public string $name,
        public int $appId,
        public string $directory,
        public \DateTimeInterface $createdAt,
        public ?\DateTimeInterface $lastUpdatedAt,
    ) {
    }
}
