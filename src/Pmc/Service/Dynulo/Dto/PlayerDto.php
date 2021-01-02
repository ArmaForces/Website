<?php

declare(strict_types=1);

namespace App\Pmc\Service\Dynulo\Dto;

class PlayerDto
{
    /**
     * Player Steam ID.
     *
     * @var int
     */
    protected $player;

    /** @var string */
    protected $nickname;

    /** @var \DateTimeImmutable */
    protected $created;

    public function __construct(int $player, string $nickname, \DateTimeImmutable $created)
    {
        $this->player = $player;
        $this->nickname = $nickname;
        $this->created = $created;
    }

    public static function fromArray(array $array): self
    {
        $timezone = new \DateTimeZone('Europe/London');

        return new self(
            $array['player'],
            $array['nickname'],
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', substr($array['created'], 0, 19), $timezone),
        );
    }

    public function getPlayer(): int
    {
        return $this->player;
    }

    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }
}
