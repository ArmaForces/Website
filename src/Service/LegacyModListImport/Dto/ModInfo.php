<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport\Dto;

class ModInfo
{
    public function __construct(
        private string $name,
        private ?string $url,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
}
