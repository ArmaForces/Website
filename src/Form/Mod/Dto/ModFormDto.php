<?php

declare(strict_types=1);

namespace App\Form\Mod\Dto;

use App\Entity\EntityInterface;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\ModInterface;
use App\Entity\Mod\SteamWorkshopMod;
use App\Form\AbstractFormDto;
use App\Form\FormDtoInterface;
use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use App\Validator\SteamWorkshopArma3ModUrl;
use App\Validator\WindowsDirectoryName;
use Symfony\Component\Validator\Constraints as Assert;

class ModFormDto extends AbstractFormDto
{
    /** @var null|string */
    protected $id;

    /**
     * @var null|string
     *
     * @Assert\NotBlank
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
     * @param null|ModInterface $entity
     *
     * @return ModFormDto
     */
    public static function fromEntity(EntityInterface $entity = null): FormDtoInterface
    {
        $self = new self();

        /** @var ModInterface $entity */
        if (!$entity instanceof ModInterface) {
            return $self;
        }

        $self->setId($entity->getId());
        $self->setName($entity->getName());
        $self->setDescription($entity->getDescription());
        $self->setType($entity->getType()->getValue());

        if ($entity instanceof SteamWorkshopMod) {
            $self->setSource(ModSourceEnum::STEAM_WORKSHOP);
            $itemId = $entity->getItemId();
            $url = SteamWorkshopHelper::itemIdToItemUrl($itemId);
            $self->setUrl($url);
        } elseif ($entity instanceof DirectoryMod) {
            $self->setSource(ModSourceEnum::DIRECTORY);
            $self->setDirectory($entity->getDirectory());
        }

        return $self;
    }

    /**
     * @param null|ModInterface $entity
     *
     * @return ModInterface
     */
    public function toEntity(EntityInterface $entity = null): EntityInterface
    {
        /** @var ModSourceEnum $source */
        $source = ModSourceEnum::get($this->getSource());

        /** @var ModTypeEnum $type */
        $type = ModTypeEnum::get($this->getType());

        if (!$entity instanceof SteamWorkshopMod && $source->is(ModSourceEnum::STEAM_WORKSHOP)) {
            $url = $this->getUrl();
            $itemId = SteamWorkshopHelper::itemUrlToItemId($url);
            $entity = new SteamWorkshopMod($this->getName(), $type, $itemId);
        } elseif (!$entity instanceof DirectoryMod && $source->is(ModSourceEnum::DIRECTORY)) {
            $entity = new DirectoryMod($this->getName(), $type, $this->getDirectory());
        }

        $entity->setName($this->getName());
        $entity->setDescription($this->getDescription());
        $entity->setType($type);

        if ($entity instanceof SteamWorkshopMod) {
            $url = $this->getUrl();
            $itemId = SteamWorkshopHelper::itemUrlToItemId($url);
            $entity->setItemId($itemId);
        } elseif ($entity instanceof DirectoryMod) {
            $entity->setDirectory($this->getDirectory());
        }

        return $entity;
    }

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
