<?php

declare(strict_types=1);

namespace App\Form\Dlc\Dto;

use App\Form\AbstractFormDto;
use App\Validator\Dlc\SteamStoreArma3DlcUrl;
use App\Validator\Dlc\UniqueSteamStoreDlc;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SteamStoreArma3DlcUrl(errorPath="url")
 * @UniqueSteamStoreDlc
 */
class DlcFormDto extends AbstractFormDto
{
    protected ?UuidInterface $id = null;

    /**
     * @Assert\Length(max=255)
     */
    protected ?string $name = null;

    /**
     * @Assert\Length(min=1, max=255)
     */
    protected ?string $description = null;

    protected ?string $url = null;

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
}
