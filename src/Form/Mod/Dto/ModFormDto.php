<?php

declare(strict_types=1);

namespace App\Form\Mod\Dto;

use App\Entity\Mod\Enum\ModSourceEnum;
use App\Form\AbstractFormDto;
use App\Validator\Mod\SteamWorkshopArma3ModUrl;
use App\Validator\Mod\UniqueDirectoryMod;
use App\Validator\Mod\UniqueSteamWorkshopMod;
use App\Validator\Mod\WindowsDirectoryName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueSteamWorkshopMod(groups={ModSourceEnum::STEAM_WORKSHOP}))
 * @UniqueDirectoryMod(groups={ModSourceEnum::DIRECTORY}))
 */
class ModFormDto extends AbstractFormDto
{
    /** @var null|string */
    protected $id;

    /**
     * @var null|string
     *
     * @Assert\NotBlank(groups={ModSourceEnum::DIRECTORY})
     * @Assert\Length(min=1, max=255)
     */
    protected $name;

    /**
     * @var null|string
     *
     * @Assert\Length(min=1, max=255)
     */
    protected $description;

    /**
     * @var null|string
     *
     * @Assert\Expression(
     *     "!(this.getType() != constant('App\\Entity\\Mod\\Enum\\ModTypeEnum::SERVER_SIDE') && this.getSource() == constant('App\\Entity\\Mod\\Enum\\ModSourceEnum::DIRECTORY'))",
     * )
     */
    protected $type;

    /**
     * @var null|string
     *
     * @Assert\Expression(
     *     "!(this.getSource() == constant('App\\Entity\\Mod\\Enum\\ModSourceEnum::DIRECTORY') && this.getType() != constant('App\\Entity\\Mod\\Enum\\ModTypeEnum::SERVER_SIDE'))",
     * )
     */
    protected $source;

    /**
     * @var null|string
     *
     * @Assert\NotBlank(groups={ModSourceEnum::STEAM_WORKSHOP})
     * @Assert\Length(min=1, max=255, groups={ModSourceEnum::STEAM_WORKSHOP})
     * @SteamWorkshopArma3ModUrl(groups={ModSourceEnum::STEAM_WORKSHOP}))
     */
    protected $url;

    /**
     * @var null|string
     *
     * @Assert\NotBlank(groups={ModSourceEnum::DIRECTORY})
     * @WindowsDirectoryName(groups={ModSourceEnum::DIRECTORY})
     */
    protected $directory;

    /**
     * {@inheritdoc}
     */
    public function resolveValidationGroups(): array
    {
        $validationGroups = parent::resolveValidationGroups();
        $validationGroups[] = $this->getSource();

        return $validationGroups;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
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
