<?php

declare(strict_types=1);

namespace App\Form\ModGroup\Dto;

use App\Entity\Mod\ModInterface;
use App\SharedKernel\Infrastructure\Validator\ModGroup\UniqueModGroupName;
use App\SharedKernel\UserInterface\Http\Form\AbstractFormDto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueModGroupName(errorPath="name")
 */
class ModGroupFormDto extends AbstractFormDto
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
}
