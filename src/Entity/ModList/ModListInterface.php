<?php

declare(strict_types=1);

namespace App\Entity\ModList;

use App\Entity\Dlc\DlcInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\ModGroup\ModGroupInterface;
use App\Entity\User\UserInterface;
use App\SharedKernel\Domain\Model\BlamableEntityInterface;
use App\SharedKernel\Domain\Model\Traits\DescribedInterface;
use App\SharedKernel\Domain\Model\Traits\NamedInterface;

interface ModListInterface extends BlamableEntityInterface, NamedInterface, DescribedInterface
{
    public function addMod(ModInterface $mod): void;

    public function removeMod(ModInterface $mod): void;

    /**
     * @return ModInterface[]
     */
    public function getMods(): array;

    /**
     * @param ModInterface[] $mods
     */
    public function setMods(array $mods): void;

    public function addModGroup(ModGroupInterface $modGroup): void;

    public function removeModGroup(ModGroupInterface $modGroup): void;

    /**
     * @return ModGroupInterface[]
     */
    public function getModGroups(): array;

    /**
     * @param ModGroupInterface[] $modGroups
     */
    public function setModGroups(array $modGroups): void;

    public function addDlc(DlcInterface $dlc): void;

    public function removeDlc(DlcInterface $dlc): void;

    /**
     * @return DlcInterface[]
     */
    public function getDlcs(): array;

    /**
     * @param DlcInterface[] $dlcs
     */
    public function setDlcs(array $dlcs): void;

    public function getOwner(): ?UserInterface;

    public function setOwner(?UserInterface $owner): void;

    public function isActive(): bool;

    public function setActive(bool $active): void;

    public function isApproved(): bool;

    public function setApproved(bool $approved): void;
}
