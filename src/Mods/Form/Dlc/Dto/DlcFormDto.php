<?php

declare(strict_types=1);

namespace App\Mods\Form\Dlc\Dto;

use App\Mods\Validator\Dlc\SteamStoreArma3DlcUrl;
use App\Mods\Validator\Dlc\UniqueDirectoryDlc;
use App\Mods\Validator\Dlc\UniqueSteamStoreDlc;
use App\Shared\Validator\Common\WindowsDirectoryName;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[SteamStoreArma3DlcUrl(errorPath: 'url')]
#[UniqueSteamStoreDlc(errorPath: 'url')]
#[UniqueDirectoryDlc(errorPath: 'directory')]
class DlcFormDto
{
    private ?UuidInterface $id = null;

    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[Assert\Length(min: 1, max: 255)]
    private ?string $description = null;

    #[Assert\NotBlank]
    private ?string $url = null;

    #[Assert\NotBlank]
    #[WindowsDirectoryName]
    private ?string $directory = null;

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
