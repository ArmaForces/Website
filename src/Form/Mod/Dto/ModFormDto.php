<?php

declare(strict_types=1);

namespace App\Form\Mod\Dto;

use App\Entity\Mod\Enum\ModSourceEnum;
use App\Form\AbstractFormDto;
use App\Validator\Mod\SteamWorkshopArma3ModUrl;
use App\Validator\Mod\UniqueDirectoryMod;
use App\Validator\Mod\UniqueSteamWorkshopMod;
use App\Validator\WindowsDirectoryName;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueSteamWorkshopMod(groups: [ModSourceEnum::STEAM_WORKSHOP->value])]
#[SteamWorkshopArma3ModUrl(groups: [ModSourceEnum::STEAM_WORKSHOP->value], errorPath: 'url', nameErrorPath: 'name')]
#[UniqueDirectoryMod(groups: [ModSourceEnum::DIRECTORY->value])]
class ModFormDto extends AbstractFormDto
{
    protected ?UuidInterface $id = null;

    #[Assert\NotBlank(groups: [ModSourceEnum::DIRECTORY->value])]
    #[Assert\Length(min: 1, max: 255)]
    protected ?string $name = null;

    #[Assert\Length(min: 1, max: 255)]
    protected ?string $description = null;

    protected ?string $type = null;

    protected ?string $status = null;

    protected ?string $source = null;

    #[Assert\NotBlank(groups: [ModSourceEnum::STEAM_WORKSHOP->value])]
    #[Assert\Length(min: 1, max: 255, groups: [ModSourceEnum::STEAM_WORKSHOP->value])]
    protected ?string $url = null;

    #[Assert\NotBlank(groups: [ModSourceEnum::DIRECTORY->value])]
    #[WindowsDirectoryName(groups: [ModSourceEnum::DIRECTORY->value])]
    protected ?string $directory = null;

    public function resolveValidationGroups(): array
    {
        $validationGroups = parent::resolveValidationGroups();
        $validationGroups[] = $this->getSource();

        return $validationGroups;
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
