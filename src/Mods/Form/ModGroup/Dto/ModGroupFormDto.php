<?php

declare(strict_types=1);

namespace App\Mods\Form\ModGroup\Dto;

use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Validator\ModGroup\UniqueModGroupName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueModGroupName(errorPath: 'name')]
class ModGroupFormDto
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

    public function __construct()
    {
        $this->mods = new ArrayCollection();
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
}
