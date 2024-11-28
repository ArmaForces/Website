<?php

declare(strict_types=1);

namespace App\Mods\Entity\ModGroup;

use App\Mods\Entity\Mod\AbstractMod;
use App\Shared\Entity\Common\AbstractBlamableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;

class ModGroup extends AbstractBlamableEntity
{
    private string $name;
    private ?string $description;
    private Collection $mods;

    public function __construct(
        UuidInterface $id,
        string $name,
        ?string $description,
        array $mods
    ) {
        parent::__construct($id);
        $this->mods = new ArrayCollection();

        $this->name = $name;
        $this->description = $description;
        $this->setMods($mods);
    }

    public function update(
        string $name,
        ?string $description,
        array $mods
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->setMods($mods);
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
}
