<?php

declare(strict_types=1);

namespace App\Form\ModList\Dto;

use App\Entity\Dlc\Dlc;
use App\Entity\Mod\AbstractMod;
use App\Entity\ModGroup\ModGroup;
use App\Entity\User\User;
use App\Validator\ModList\UniqueModListName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueModListName(errorPath: 'name')]
class ModListFormDto
{
    private ?UuidInterface $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name = null;

    #[Assert\Length(min: 1, max: 255)]
    private ?string $description = null;

    /**
     * @var Collection<AbstractMod>
     */
    private Collection $mods;

    /**
     * @var Collection<ModGroup>
     */
    private Collection $modGroups;

    /**
     * @var Collection<Dlc>
     */
    private Collection $dlcs;

    private ?User $owner = null;

    private bool $active = true;

    private bool $approved = false;

    public function __construct()
    {
        $this->mods = new ArrayCollection();
        $this->modGroups = new ArrayCollection();
        $this->dlcs = new ArrayCollection();
    }

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

    public function addMod(AbstractMod $mod): void
    {
        if ($this->mods->contains($mod)) {
            return;
        }

        $this->mods->add($mod);
    }

    public function removeMod(AbstractMod $mod): void
    {
        if (!$this->mods->contains($mod)) {
            return;
        }

        $this->mods->removeElement($mod);
    }

    /**
     * @return AbstractMod[]
     */
    public function getMods(): array
    {
        return $this->mods->toArray();
    }

    /**
     * @param AbstractMod[] $mods
     */
    public function setMods(array $mods): void
    {
        $this->mods->clear();
        foreach ($mods as $mod) {
            $this->addMod($mod);
        }
    }

    public function addModGroup(ModGroup $modGroup): void
    {
        if ($this->modGroups->contains($modGroup)) {
            return;
        }

        $this->modGroups->add($modGroup);
    }

    public function removeModGroup(ModGroup $modGroup): void
    {
        if (!$this->modGroups->contains($modGroup)) {
            return;
        }

        $this->modGroups->removeElement($modGroup);
    }

    /**
     * @return ModGroup[]
     */
    public function getModGroups(): array
    {
        return $this->modGroups->toArray();
    }

    /**
     * @param ModGroup[] $modGroups
     */
    public function setModGroups(array $modGroups): void
    {
        $this->modGroups->clear();
        foreach ($modGroups as $modGroup) {
            $this->addModGroup($modGroup);
        }
    }

    public function addDlc(Dlc $dlc): void
    {
        if ($this->dlcs->contains($dlc)) {
            return;
        }

        $this->dlcs->add($dlc);
    }

    public function removeDlc(Dlc $dlc): void
    {
        if (!$this->dlcs->contains($dlc)) {
            return;
        }

        $this->dlcs->removeElement($dlc);
    }

    /**
     * @return Dlc[]
     */
    public function getDlcs(): array
    {
        return $this->dlcs->toArray();
    }

    /**
     * @param Dlc[] $dlcs
     */
    public function setDlcs(array $dlcs): void
    {
        $this->dlcs->clear();
        foreach ($dlcs as $dlc) {
            $this->addDlc($dlc);
        }
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): void
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

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }
}
