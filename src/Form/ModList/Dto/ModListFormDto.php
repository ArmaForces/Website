<?php

declare(strict_types=1);

namespace App\Form\ModList\Dto;

use App\Entity\Mod\ModInterface;
use App\Entity\ModGroup\ModGroupInterface;
use App\Entity\User\UserInterface;
use App\Form\AbstractFormDto;
use App\Validator\ModList\UniqueModListName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueModListName(errorPath="name")
 */
class ModListFormDto extends AbstractFormDto
{
    /** @var null|string */
    protected $id;

    /**
     * @var null|string
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    protected $name;

    /**
     * @var null|string
     *
     * @Assert\Length(max=255)
     */
    protected $description;

    /**
     * @var Collection|ModInterface[]
     */
    protected $mods;

    /**
     * @var Collection|ModGroupInterface[]
     */
    protected $modGroups;

    /**
     * @var null|UserInterface
     */
    protected $owner;

    /**
     * @var bool
     */
    protected $active = true;

    public function __construct()
    {
        $this->mods = new ArrayCollection();
        $this->modGroups = new ArrayCollection();
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

    public function addMod(ModInterface $mod): void
    {
        if ($this->mods->contains($mod)) {
            return;
        }

        $this->mods->add($mod);
    }

    public function removeMod(ModInterface $mod): void
    {
        if (!$this->mods->contains($mod)) {
            return;
        }

        $this->mods->removeElement($mod);
    }

    /**
     * @return ModInterface[]
     */
    public function getMods(): array
    {
        return $this->mods->toArray();
    }

    /**
     * @param ModInterface[] $mods
     */
    public function setMods(array $mods): void
    {
        $this->mods->clear();
        foreach ($mods as $mod) {
            $this->addMod($mod);
        }
    }

    public function addModGroup(ModGroupInterface $modGroup): void
    {
        if ($this->modGroups->contains($modGroup)) {
            return;
        }

        $this->modGroups->add($modGroup);
    }

    public function removeModGroup(ModGroupInterface $modGroup): void
    {
        if (!$this->modGroups->contains($modGroup)) {
            return;
        }

        $this->modGroups->removeElement($modGroup);
    }

    /**
     * @return ModGroupInterface[]
     */
    public function getModGroups(): array
    {
        return $this->modGroups->toArray();
    }

    /**
     * @param ModGroupInterface[] $modGroups
     */
    public function setModGroups(array $modGroups): void
    {
        $this->modGroups->clear();
        foreach ($modGroups as $modGroup) {
            $this->addModGroup($modGroup);
        }
    }

    public function getOwner(): ?UserInterface
    {
        return $this->owner;
    }

    public function setOwner(?UserInterface $owner): void
    {
        $this->owner = $owner;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
