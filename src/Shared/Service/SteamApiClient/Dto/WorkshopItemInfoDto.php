<?php

declare(strict_types=1);

namespace App\Shared\Service\SteamApiClient\Dto;

class WorkshopItemInfoDto
{
    public function __construct(
        private ?int $id,
        private ?string $name,
        private ?int $gameId
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getGameId(): ?int
    {
        return $this->gameId;
    }
}
