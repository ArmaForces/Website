<?php

declare(strict_types=1);

namespace App\Form\ModList\Dto;

use App\Entity\EntityInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\Mod\SteamWorkshopModInterface;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Form\AbstractFormDto;
use App\Form\FormDtoInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var Collection|SteamWorkshopModInterface[]
     */
    protected $steamWorkshopMods;

    public function __construct()
    {
        $this->steamWorkshopMods = new ArrayCollection();
    }

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

        $self->clearSteamWorkshopMods();
        foreach ($entity->getMods() as $mod) {
            if ($mod instanceof SteamWorkshopModInterface) {
                $self->addSteamWorkshopMod($mod);
            }
        }

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

        $entity->clearMods();
        foreach ($this->getSteamWorkshopMods() as $steamWorkshopMod) {
            $entity->addMod($steamWorkshopMod);
        }

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

    public function addSteamWorkshopMod(SteamWorkshopModInterface $steamWorkshopMod): void
    {
        if ($this->steamWorkshopMods->contains($steamWorkshopMod)) {
            return;
        }

        $this->steamWorkshopMods->add($steamWorkshopMod);
    }

    public function removeSteamWorkshopMod(SteamWorkshopModInterface $steamWorkshopMod): void
    {
        if (!$this->steamWorkshopMods->contains($steamWorkshopMod)) {
            return;
        }

        $this->steamWorkshopMods->removeElement($steamWorkshopMod);
    }

    /**
     * @return SteamWorkshopModInterface[]
     */
    public function getSteamWorkshopMods(): array
    {
        return $this->steamWorkshopMods->toArray();
    }

    public function clearSteamWorkshopMods(): void
    {
        $this->steamWorkshopMods->clear();
    }
}
