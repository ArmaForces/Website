<?php

declare(strict_types=1);

namespace App\Form\ModList\Dto;

use App\Entity\Dlc\DlcInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\ModGroup\ModGroupInterface;
use App\SharedKernel\Infrastructure\Validator\ModList\UniqueModListName;
use App\SharedKernel\UserInterface\Http\Form\AbstractFormDto;
use App\UserManagement\Domain\Model\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueModListName(errorPath="name")
 */
class ModListFormDto extends AbstractFormDto
{
    protected ?UuidInterface $id = null;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    protected ?string $name = null;

    /**
     * @Assert\Length(min=1, max=255)
     */
    protected ?string $description = null;

    /**
     * @var Collection<ModInterface>
     */
    protected Collection $mods;

    /**
     * @var Collection<ModGroupInterface>
     */
    protected Collection $modGroups;

    /**
     * @var Collection<DlcInterface>
     */
    protected Collection $dlcs;

    protected ?UserInterface $owner = null;

    protected bool $active = true;

    protected bool $approved = false;

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

    public function addDlc(DlcInterface $dlc): void
    {
        if ($this->dlcs->contains($dlc)) {
            return;
        }

        $this->dlcs->add($dlc);
    }

    public function removeDlc(DlcInterface $dlc): void
    {
        if (!$this->dlcs->contains($dlc)) {
            return;
        }

        $this->dlcs->removeElement($dlc);
    }

    /**
     * @return DlcInterface[]
     */
    public function getDlcs(): array
    {
        return $this->dlcs->toArray();
    }

    /**
     * @param DlcInterface[] $dlcs
     */
    public function setDlcs(array $dlcs): void
    {
        $this->dlcs->clear();
        foreach ($dlcs as $dlc) {
            $this->addDlc($dlc);
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

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }
}
