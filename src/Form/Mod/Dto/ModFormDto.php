<?php

declare(strict_types=1);

namespace App\Form\Mod\Dto;

use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\ModTag\ModTagInterface;
use App\Form\AbstractFormDto;
use App\Validator\Mod\SteamWorkshopArma3ModUrl;
use App\Validator\Mod\UniqueDirectoryMod;
use App\Validator\Mod\UniqueSteamWorkshopMod;
use App\Validator\Mod\WindowsDirectoryName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueSteamWorkshopMod(groups={ModSourceEnum::STEAM_WORKSHOP}))
 * @UniqueDirectoryMod(groups={ModSourceEnum::DIRECTORY}))
 */
class ModFormDto extends AbstractFormDto
{
    /** @var null|UuidInterface */
    protected $id;

    /**
     * @var null|string
     *
     * @Assert\NotBlank(groups={ModSourceEnum::DIRECTORY})
     * @Assert\Length(min=1, max=255)
     */
    protected $name;

    /**
     * @var null|string
     *
     * @Assert\Length(min=1, max=255)
     */
    protected $description;

    /**
     * @var null|string
     *
     * @Assert\Expression(
     *     "!(this.getType() != constant('App\\Entity\\Mod\\Enum\\ModTypeEnum::SERVER_SIDE') && this.getSource() == constant('App\\Entity\\Mod\\Enum\\ModSourceEnum::DIRECTORY'))",
     * )
     */
    protected $type;

    /**
     * @var null|string
     */
    protected $status;

    /** @var Collection|ModTagInterface[] */
    protected $tags;

    /**
     * @var null|string
     *
     * @Assert\Expression(
     *     "!(this.getSource() == constant('App\\Entity\\Mod\\Enum\\ModSourceEnum::DIRECTORY') && this.getType() != constant('App\\Entity\\Mod\\Enum\\ModTypeEnum::SERVER_SIDE'))",
     * )
     */
    protected $source;

    /**
     * @var null|string
     *
     * @Assert\NotBlank(groups={ModSourceEnum::STEAM_WORKSHOP})
     * @Assert\Length(min=1, max=255, groups={ModSourceEnum::STEAM_WORKSHOP})
     * @SteamWorkshopArma3ModUrl(groups={ModSourceEnum::STEAM_WORKSHOP}))
     */
    protected $url;

    /**
     * @var null|string
     *
     * @Assert\NotBlank(groups={ModSourceEnum::DIRECTORY})
     * @WindowsDirectoryName(groups={ModSourceEnum::DIRECTORY})
     */
    protected $directory;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function resolveValidationGroups(): array
    {
        $validationGroups = parent::resolveValidationGroups();
        $validationGroups[] = $this->getSource();

        return $validationGroups;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function addTag(ModTagInterface $tag): void
    {
        if ($this->tags->contains($tag)) {
            return;
        }

        $this->tags->add($tag);
    }

    public function removeTag(ModTagInterface $tag): void
    {
        if (!$this->tags->contains($tag)) {
            return;
        }

        $this->tags->removeElement($tag);
    }

    public function getTags(): array
    {
        return $this->tags->toArray();
    }

    public function setTags(array $tags): void
    {
        $this->tags->clear();
        foreach ($tags as $tag) {
            $this->addTag($tag);
        }
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(?string $directory): void
    {
        $this->directory = $directory;
    }
}
