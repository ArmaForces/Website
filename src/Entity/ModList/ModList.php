<?php

declare(strict_types=1);

namespace App\Entity\ModList;

use App\Entity\AbstractBlamableEntity;
use App\Entity\Dlc\Dlc;
use App\Entity\Mod\AbstractMod;
use App\Entity\ModGroup\ModGroup;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class ModList extends AbstractBlamableEntity
{
    private string $name;
    private ?string $description;
    private Collection $mods;
    private Collection $modGroups;
    private Collection $dlcs;
    private ?User $owner;
    private bool $active;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @return Dlc[]
     */
    public function getDlcs(): array
    {
        return $this->dlcs->toArray();
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
