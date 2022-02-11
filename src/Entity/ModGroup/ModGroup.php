<?php

declare(strict_types=1);

namespace App\Entity\ModGroup;

use App\Entity\AbstractBlamableEntity;
use App\Entity\Mod\ModInterface;
use App\Entity\Traits\DescribedTrait;
use App\Entity\Traits\NamedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class ModGroup extends AbstractBlamableEntity implements ModGroupInterface
{
    use NamedTrait;
    use DescribedTrait;

    private Collection $mods;

    public function __construct(
        UuidInterface $id,
        private string $name
    ) {
        parent::__construct($id);

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
