<?php

declare(strict_types=1);

namespace App\Http\Mission\Dto;

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

    public function __construct(float $id, string $title, \DateTimeImmutable $date, \DateTimeImmutable $closeDate, string $description, int $freeSlots, int $allSlots, ?string $image = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->date = $date;
        $this->closeDate = $closeDate;
        $this->image = $image;
        $this->description = $description;
        $this->freeSlots = $freeSlots;
        $this->allSlots = $allSlots;
    }

    public static function fromArray(array $array): MissionDto
    {
        return new self(
            ($array['archive'] ?? false) ? -1 : $array['id'],
            $array['title'],
            // TODO pr for remote Bot api with dates as Epoch
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', substr($array['date'], 0, 19)),
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', substr($array['closeDate'], 0, 19)),
            $array['description'],
            $array['freeSlots'],
            $array['allSlots'],
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

    public function isArchived(): bool
    {
        return -1 !== $this->getId();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
