<?php

declare(strict_types=1);

namespace App\Mods\Form\Mod\Dto;

use App\Mods\Entity\Mod\Enum\ModSourceEnum;
use App\Mods\Validator\Mod\SteamWorkshopArma3ModUrl;
use App\Mods\Validator\Mod\UniqueDirectoryMod;
use App\Mods\Validator\Mod\UniqueSteamWorkshopMod;
use App\Shared\Validator\Common\WindowsDirectoryName;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueSteamWorkshopMod(groups: [ModSourceEnum::STEAM_WORKSHOP->value], errorPath: 'url')]
#[SteamWorkshopArma3ModUrl(groups: [ModSourceEnum::STEAM_WORKSHOP->value], errorPath: 'url', nameErrorPath: 'name')]
#[UniqueDirectoryMod(groups: [ModSourceEnum::DIRECTORY->value], errorPath: 'directory')]
class ModFormDto
{
    private ?UuidInterface $id = null;

    #[Assert\NotBlank(groups: [ModSourceEnum::DIRECTORY->value])]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    #[Assert\Length(min: 1, max: 255)]
    private ?string $description = null;

    private ?string $type = null;

    private ?string $status = null;

    private ?string $source = null;

    #[Assert\NotBlank(groups: [ModSourceEnum::STEAM_WORKSHOP->value])]
    #[Assert\Length(min: 1, max: 255, groups: [ModSourceEnum::STEAM_WORKSHOP->value])]
    private ?string $url = null;

    #[Assert\NotBlank(groups: [ModSourceEnum::DIRECTORY->value])]
    #[WindowsDirectoryName(groups: [ModSourceEnum::DIRECTORY->value])]
    private ?string $directory = null;

    public function resolveValidationGroups(): array
    {
        return [Constraint::DEFAULT_GROUP, $this->getSource()];
    }

    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function setId(?UuidInterface $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(?string $directory): void
    {
        $this->directory = $directory;
    }
}
