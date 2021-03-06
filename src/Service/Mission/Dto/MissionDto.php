<?php

declare(strict_types=1);

namespace App\Service\Mission\Dto;

class MissionDto
{
    protected ?int $id;
    protected string $title;
    protected \DateTimeImmutable $date;
    protected \DateTimeImmutable $closeDate;
    protected string $description;
    protected int $freeSlots;
    protected int $allSlots;
    protected ?string $image;
    protected string $state;

    public function __construct(
        ?int $id,
        string $title,
        \DateTimeImmutable $date,
        \DateTimeImmutable $closeDate,
        string $description,
        int $freeSlots,
        int $allSlots,
        string $state,
        ?string $image = null
    ) {
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
            $array['id'] ?? null,
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

    public function getId(): ?int
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

    public function getState(): string
    {
        return $this->state;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
