<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\AbstractBlamableEntity;
use App\Entity\Mod\Enum\ModStatusEnum;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractMod extends AbstractBlamableEntity
{
    protected string $name;
    protected ?string $description;
    protected ?ModStatusEnum $status;

    public function __construct(
        UuidInterface $id,
        string $name,
        ?string $description,
        ?ModStatusEnum $status
    ) {
        parent::__construct($id);

        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getStatus(): ?ModStatusEnum
    {
        return $this->status;
    }
}
