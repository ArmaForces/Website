<?php

declare(strict_types=1);

namespace App\Service\Mission\Dto;

class MissionDto
{
    /** @var float */
    protected $id;

    /** @var string */
    protected $title;

    /** @var \DateTimeImmutable */
    protected $date;

    /** @var \DateTimeImmutable */
    protected $closeDate;

    /** @var string */
    protected $description;

    /** @var int */
    protected $freeSlots;

    /** @var int */
    protected $allSlots;

    /** @var null|string */
    protected $image;

    /** @var string */
    protected $state;

    public function __construct(float $id, string $title, \DateTimeImmutable $date, \DateTimeImmutable $closeDate, string $description, int $freeSlots, int $allSlots, string $state, ?string $image = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->closeDate = $closeDate;
        $this->description = $description;
        $this->freeSlots = $freeSlots;
        $this->allSlots = $allSlots;
        $this->state = $state;
        $this->image = $image;
    }

    /**
     * @param array<mixed> $array
     */
    public static function fromArray(array $array): self
    {
        $timezone = new \DateTimeZone('Europe/Warsaw');

        return new self(
            ($array['archive'] ?? false) ? -1 : $array['id'],
            $array['title'],
            // TODO pr for remote Bot api with dates as Epoch
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', substr($array['date'], 0, 19), $timezone),
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', substr($array['closeDate'], 0, 19), $timezone),
            $array['description'],
            $array['freeSlots'],
            $array['allSlots'],
            $array['state'],
            $array['image']
        );
    }

    public function getId(): float
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getCloseDate(): \DateTimeImmutable
    {
        return $this->closeDate;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFreeSlots(): int
    {
        return $this->freeSlots;
    }

    public function getAllSlots(): int
    {
        return $this->allSlots;
    }

    public function getOccupiedSlots(): int
    {
        return $this->getAllSlots() - $this->getFreeSlots();
    }

    public function isArchived(): bool
    {
        return -1 === (int) $this->getId();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getState(): string
    {
        return $this->state;
    }
}
