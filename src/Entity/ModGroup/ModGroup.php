<?php

declare(strict_types=1);

namespace App\Entity\ModGroup;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Mod\ModInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class ModGroup extends AbstractDescribedEntity implements ModGroupInterface
{
    /** @var Collection|ModInterface[] */
    protected $mods;

    public function __construct(UuidInterface $id, string $name)
    {
        parent::__construct($id, $name);

        $this->mods = new ArrayCollection();
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
}
