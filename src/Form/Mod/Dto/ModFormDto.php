<?php

declare(strict_types=1);

namespace App\Form\Mod\Dto;

use App\Entity\EntityInterface;
use App\Entity\Mod\Mod;
use App\Enum\Mod\ModSourceEnum;
use App\Form\AbstractFormDto;
use App\Form\FormDtoInterface;
use App\Validator\SteamWorkshopModUrl;
use App\Validator\WindowsDirectoryPath;
use Symfony\Component\Validator\Constraints as Assert;

class ModFormDto extends AbstractFormDto
{
    /**
     * @var null|string
     */
    protected $id;

    /**
     * @var null|string
     *
     * @Assert\Length(min=1, max=255)
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @var null|string
     *
     * @SteamWorkshopModUrl(groups={ModSourceEnum::STEAM_WORKSHOP})
     * @WindowsDirectoryPath(groups={ModSourceEnum::DIRECTORY})
     * @Assert\Length(min=1, max=255, groups={ModSourceEnum::DIRECTORY})
     * @Assert\NotBlank
     */
    protected $path;

    /**
     * @var null|string
     *
     * @Assert\Expression(
     *     "!(this.getUsedBy() == constant('App\\Enum\\Mod\\ModUsedByEnum::CLIENT') && this.getSource() != constant('App\\Enum\\Mod\\ModSourceEnum::STEAM_WORKSHOP'))",
     *     message="Incorrect values combination"
     * )
     *
     * @Assert\Expression(
     *     "!(this.getUsedBy() == constant('App\\Enum\\Mod\\ModUsedByEnum::SERVER') && this.getType() != constant('App\\Enum\\Mod\\ModTypeEnum::REQUIRED'))",
     *     message="Incorrect values combination"
     * )
     */
    protected $usedBy;

    /**
     * @var null|string
     *
     * @Assert\Expression(
     *     "!(this.getUsedBy() == constant('App\\Enum\\Mod\\ModUsedByEnum::SERVER') && this.getType() != constant('App\\Enum\\Mod\\ModTypeEnum::REQUIRED'))",
     *     message="Incorrect values combination"
     * )
     */
    protected $type;

    /**
     * @var null|string
     *
     * @Assert\Expression(
     *     "!(this.getUsedBy() == constant('App\\Enum\\Mod\\ModUsedByEnum::CLIENT') && this.getSource() != constant('App\\Enum\\Mod\\ModSourceEnum::STEAM_WORKSHOP'))",
     *     message="Incorrect values combination"
     * )
     */
    protected $source;

    /**
     * @param null|Mod $entity
     */
    public static function fromEntity(EntityInterface $entity = null): FormDtoInterface
    {
        $self = new self();

        if (!$entity) {
            return $self;
        }

        $self->setId($entity->getId());
        $self->setName($entity->getName());
        $self->setUsedBy($entity->getUsedBy());
        $self->setType($entity->getType());
        $self->setSource($entity->getSource());
        $self->setPath($entity->getPath());

        return $self;
    }

    /**
     * @param null|Mod $entity
     */
    public function toEntity(EntityInterface $entity = null): EntityInterface
    {
        if (!$entity) {
            $entity = new Mod(
                $this->getName(),
                $this->getPath()
            );
        }

        $entity->setName($this->getName());
        $entity->setPath($this->getPath());
        $entity->setUsedBy($this->getUsedBy());
        $entity->setType($this->getType());
        $entity->setSource($this->getSource());

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveValidationGroups(): array
    {
        $validationGroups = parent::resolveValidationGroups();

        if (ModSourceEnum::STEAM_WORKSHOP === $this->getSource()) {
            $validationGroups[] = ModSourceEnum::STEAM_WORKSHOP;
        } elseif (ModSourceEnum::DIRECTORY === $this->getSource()) {
            $validationGroups[] = ModSourceEnum::DIRECTORY;
        }

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getUsedBy(): ?string
    {
        return $this->usedBy;
    }

    public function setUsedBy(?string $usedBy): void
    {
        $this->usedBy = $usedBy;
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
}
