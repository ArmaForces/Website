<?php

declare(strict_types=1);

namespace App\Service\SteamWorkshop\Dto;

class SteamWorkshopItemInfoDto
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $name;

    /** @var int */
    protected $gameId;

    public function __construct(int $id, string $name, int $gameId)
    {
        $this->id = $id;
        $this->name = $name;
        $this->gameId = $gameId;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }
}
