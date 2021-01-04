<?php

declare(strict_types=1);

namespace App\Pmc\Service\Dynulo\Dto;

class PlayerPurchaseDto
{
    /**
     * Player Steam ID.
     *
     * @var int
     */
    protected $player;

    /**
     * Amount of money charged for purchase.
     *
     * @var int
     */
    protected $amount;

    /** @var int */
    protected $quantity;

    /** @var string */
    protected $class;

    /** @var \DateTimeImmutable */
    protected $created;

    public function __construct(int $player, int $amount, int $quantity, string $class, \DateTimeImmutable $created)
    {
        $this->player = $player;
        $this->amount = $amount;
        $this->quantity = $quantity;
        $this->class = $class;
        $this->created = $created;
    }

    public static function fromArray(array $array): self
    {
        $timezone = new \DateTimeZone('Europe/London');

        return new self(
            $array['player'],
            $array['amount'],
            $array['quantity'],
            $array['class'],
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', substr($array['created'], 0, 19), $timezone),
        );
    }

    public function getPlayer(): int
    {
        return $this->player;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }
}
