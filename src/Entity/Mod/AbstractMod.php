<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\AbstractBlamableEntity;
use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Traits\DescribedTrait;
use App\Entity\Traits\NamedTrait;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractMod extends AbstractBlamableEntity implements ModInterface
{
    use NamedTrait;
    use DescribedTrait;

    protected ModTypeEnum $type;
    protected ?ModStatusEnum $status = null;

    public function __construct(UuidInterface $id, string $name, ModTypeEnum $type)
    {
        parent::__construct($id);

        $this->name = $name;
        $this->type = $type;
    }

    public function isSteamWorkshopMod(): bool
    {
        return $this instanceof SteamWorkshopMod;
    }

    public function isDirectoryMod(): bool
    {
        return $this instanceof DirectoryMod;
    }

    public function getType(): ModTypeEnum
    {
        return $this->type;
    }

    public function setType(ModTypeEnum $type): void
    {
        $this->type = $type;
    }

    public function isTypeServerSide(): bool
    {
        return $this->getType()->is(ModTypeEnum::SERVER_SIDE);
    }

    public function isTypeRequired(): bool
    {
        return $this->getType()->is(ModTypeEnum::REQUIRED);
    }

    public function isTypeOptional(): bool
    {
        return $this->getType()->is(ModTypeEnum::OPTIONAL);
    }

    public function isTypeClientSide(): bool
    {
        return $this->getType()->is(ModTypeEnum::CLIENT_SIDE);
    }

    public function isUserSelectable(): bool
    {
        return $this->isSteamWorkshopMod() && \in_array($this->getType()->getValue(), [
            ModTypeEnum::CLIENT_SIDE,
            ModTypeEnum::OPTIONAL,
        ], true);
    }

    public function getStatus(): ?ModStatusEnum
    {
        return $this->status;
    }

    public function setStatus(?ModStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function isStatusDeprecated(): bool
    {
        return $this->getStatus() instanceof ModStatusEnum && $this->getStatus()->is(ModStatusEnum::DEPRECATED);
    }

    public function isStatusBroken(): bool
    {
        return $this->getStatus() instanceof ModStatusEnum && $this->getStatus()->is(ModStatusEnum::BROKEN);
    }

    public function isStatusDisabled(): bool
    {
        return $this->getStatus() instanceof ModStatusEnum && $this->getStatus()->is(ModStatusEnum::DISABLED);
    }
}
