<?php

declare(strict_types=1);

namespace App\Form\ModTag\Dto;

use App\Form\AbstractFormDto;
use App\Validator\ModTag\UniqueModTagName;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueModTagName(errorPath="name")
 */
class ModTagFormDto extends AbstractFormDto
{
    /** @var null|UuidInterface */
    protected $id;

    /**
     * @var null|string
     *
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    protected $name;

    /**
     * @var null|string
     *
     * @Assert\Length(min=1, max=255)
     */
    protected $description;

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
}
