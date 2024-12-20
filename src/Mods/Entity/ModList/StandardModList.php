<?php

declare(strict_types=1);

namespace App\Mods\Entity\ModList;

use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\ModGroup\ModGroup;
use App\Users\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class StandardModList extends AbstractModList
{
    private Collection $mods;
    private Collection $modGroups;
    private Collection $dlcs;
    private ?User $owner;
    private bool $approved;

    public function __construct(
        UuidInterface $id,
        string $name,
        ?string $description,
        array $mods,
        array $modGroups,
        array $dlcs,
        ?User $owner,
        bool $active,
        bool $approved,
    ) {
        parent::__construct($id);
        $this->mods = new ArrayCollection();
        $this->modGroups = new ArrayCollection();
        $this->dlcs = new ArrayCollection();

        $this->name = $name;
        $this->description = $description;
        $this->setMods($mods);
        $this->setModGroups($modGroups);
        $this->setDlcs($dlcs);
        $this->owner = $owner;
        $this->active = $active;
        $this->approved = $approved;
    }

    public function update(
        string $name,
        ?string $description,
        array $mods,
        array $modGroups,
        array $dlcs,
        ?User $owner,
        bool $active,
        bool $approved,
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->setMods($mods);
        $this->setModGroups($modGroups);
        $this->setDlcs($dlcs);
        $this->owner = $owner;
        $this->active = $active;
        $this->approved = $approved;
    }

    /**
     * @return AbstractMod[]
     */
    public function getMods(): array
    {
        return $this->mods->toArray();
    }

    /**
     * @return ModGroup[]
     */
    public function getModGroups(): array
    {
        return $this->modGroups->toArray();
    }

    /**
     * @return Dlc[]
     */
    public function getDlcs(): array
    {
        return $this->dlcs->toArray();
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    private function setMods(array $mods): void
    {
        $this->mods->clear();
        foreach ($mods as $mod) {
            $this->addMod($mod);
        }
    }

    private function addMod(AbstractMod $mod): void
    {
        if ($this->mods->contains($mod)) {
            return;
        }

        $this->mods->add($mod);
    }

    private function setModGroups(array $modGroups): void
    {
        $this->modGroups->clear();
        foreach ($modGroups as $modGroup) {
            $this->addModGroup($modGroup);
        }
    }

    private function addModGroup(ModGroup $modGroup): void
    {
        if ($this->modGroups->contains($modGroup)) {
            return;
        }

        $this->modGroups->add($modGroup);
    }

    private function setDlcs(array $dlcs): void
    {
        $this->dlcs->clear();
        foreach ($dlcs as $dlc) {
            $this->addDlc($dlc);
        }
    }

    private function addDlc(Dlc $dlc): void
    {
        if ($this->dlcs->contains($dlc)) {
            return;
        }

        $this->dlcs->add($dlc);
    }
}
