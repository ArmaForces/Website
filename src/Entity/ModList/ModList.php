<?php

declare(strict_types=1);

namespace App\Entity\ModList;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Mod\ModInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ModList extends AbstractDescribedEntity implements ModListInterface
{
    /** @var Collection|ModInterface[] */
    protected $mods;

    public function __construct(string $name)
    {
        parent::__construct($name);

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
