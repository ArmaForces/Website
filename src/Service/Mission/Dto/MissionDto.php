<?php

declare(strict_types=1);

namespace App\Service\Mission\Dto;

class MissionDto
{
    public function __construct(
        private ?int $id,
        private string $title,
        private \DateTimeImmutable $date,
        private \DateTimeImmutable $closeDate,
        private string $description,
        private string $modlistName,
        private int $freeSlots,
        private int $allSlots,
        private string $state,
        private ?string $image = null
    ) {
    }

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
            $array['modlistName'],
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

    public function getModlist(): string
    {
        return $this->modlistName;
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
