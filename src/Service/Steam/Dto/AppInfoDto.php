<?php

declare(strict_types=1);

namespace App\Service\Steam\Dto;

class AppInfoDto
{
    protected ?int $id;
    protected ?string $name;
    protected ?string $type;
    protected ?int $gameId;

    public function __construct(?int $id, ?string $name, ?string $type, ?int $gameId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->gameId = $gameId;
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
