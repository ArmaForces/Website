<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Service\Steam\Dto;

class AppInfoDto
{
    public function __construct(
        private ?int $id,
        private ?string $name,
        private ?string $type,
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getGameId(): ?int
    {
        return $this->gameId;
    }
}
