<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\ModList\ModListInterface;
use Doctrine\Common\Collections\Collection;

abstract class AbstractMod extends AbstractDescribedEntity implements ModInterface
{
    /** @var ModTypeEnum */
    protected $type;

    /** @var Collection|ModListInterface[] */
    protected $modsLists;

    public function __construct(string $name, ModTypeEnum $type)
    {
        parent::__construct($name);

        $this->type = $type;
    }

    public function getType(): ModTypeEnum
    {
        return $this->type;
    }

    public function setType(ModTypeEnum $type): void
    {
        $this->type = $type;
    }

    public function addModList(ModListInterface $modsList): void
    {
        if ($this->modsLists->contains($modsList)) {
            return;
        }

        $this->modsLists->add($modsList);
        $modsList->addMod($this);
    }

    public function removeModList(ModListInterface $modsList): void
    {
        if (!$this->modsLists->contains($modsList)) {
            return;
        }

        $this->modsLists->removeElement($modsList);
        $modsList->removeMod($this);
    }

    public function getModLists(): array
    {
        return $this->modsLists->toArray();
    }
}
