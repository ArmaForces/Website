<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Mod\Enum\ModTypeEnum;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractMod extends AbstractDescribedEntity implements ModInterface
{
    /** @var ModTypeEnum */
    protected $type;

    public function __construct(UuidInterface $id, string $name, ModTypeEnum $type)
    {
        parent::__construct($id, $name);

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
}
