<?php

declare(strict_types=1);

namespace App\Entity\ModList;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Mod\ModInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

class ModList extends AbstractDescribedEntity implements ModListInterface
{
    /** @var Collection|ModInterface[] */
    protected $mods;

    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->mods = new ArrayCollection();
    }

    /**
     * TODO: Replace with Cloneable interface to avoid overriding magic method behavior.
     */
    public function __clone()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->setCreatedAt(null);
        $this->setLastUpdatedAt(null);
    }

    public function addMod(ModInterface $mod): void
    {
        if ($this->mods->contains($mod)) {
            return;
        }

        $this->mods->add($mod);
        $mod->addModList($this);
    }

    public function removeMod(ModInterface $mod): void
    {
        if (!$this->mods->contains($mod)) {
            return;
        }

        $this->mods->removeElement($mod);
        $mod->removeModList($this);
    }

    public function getMods(): array
    {
        return $this->mods->toArray();
    }

    public function clearMods(): void
    {
        $this->mods->clear();
    }
}
