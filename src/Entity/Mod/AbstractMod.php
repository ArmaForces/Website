<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\ModList\ModListInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class AbstractMod extends AbstractDescribedEntity implements ModInterface
{
    /** @var ModTypeEnum */
    protected $type;

    /** @var Collection|ModListInterface[] */
    protected $modLists;

    public function __construct(string $name, ModTypeEnum $type)
    {
        parent::__construct($name);

        $this->type = $type;
        $this->modLists = new ArrayCollection();
    }

    public function getType(): ModTypeEnum
    {
        return $this->type;
    }

    public function setType(ModTypeEnum $type): void
    {
        $this->type = $type;
    }

    public function addModList(ModListInterface $modList): void
    {
        if ($this->modLists->contains($modList)) {
            return;
        }

        $this->modLists->add($modList);
        $modList->addMod($this);
    }

    public function removeModList(ModListInterface $modList): void
    {
        if (!$this->modLists->contains($modList)) {
            return;
        }

        $this->modLists->removeElement($modList);
        $modList->removeMod($this);
    }

    public function getModLists(): array
    {
        return $this->modLists->toArray();
    }
}
