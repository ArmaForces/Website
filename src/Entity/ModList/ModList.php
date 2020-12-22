<?php

declare(strict_types=1);

namespace App\Entity\ModList;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Mod\ModInterface;
use App\Entity\ModGroup\ModGroupInterface;
use App\Entity\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class ModList extends AbstractDescribedEntity implements ModListInterface
{
    /** @var Collection|ModInterface[] */
    protected $mods;

    /** @var Collection|ModGroupInterface[] */
    protected $modGroups;

    /** @var null|UserInterface */
    protected $owner;

    /** @var bool */
    protected $active = true;

    public function __construct(UuidInterface $id, string $name)
    {
        parent::__construct($id, $name);

        $this->mods = new ArrayCollection();
        $this->modGroups = new ArrayCollection();
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

    public function getMods(): array
    {
        return $this->mods->toArray();
    }

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

    public function getModGroups(): array
    {
        return $this->modGroups->toArray();
    }

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
