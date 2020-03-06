<?php

declare(strict_types=1);

namespace App\Form\ModList\Dto;

use App\Entity\EntityInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Form\AbstractFormDto;
use App\Form\FormDtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ModListFormDto extends AbstractFormDto
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
     * @param null|ModListInterface $entity
     *
     * @return ModListFormDto
     */
    public static function fromEntity(EntityInterface $entity = null): FormDtoInterface
    {
        $self = new self();

        /** @var ModInterface $entity */
        if (!$entity instanceof ModListInterface) {
            return $self;
        }

        $self->setId($entity->getId());
        $self->setName($entity->getName());
        $self->setDescription($entity->getDescription());

        return $self;
    }

    /**
     * @param null|ModListInterface $entity
     *
     * @return ModListInterface
     */
    public function toEntity(EntityInterface $entity = null): EntityInterface
    {
        if (!$entity instanceof ModListInterface) {
            $entity = new ModList($this->getName());
        }

        $entity->setName($this->getName());
        $entity->setDescription($this->getDescription());

        return $entity;
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
}
