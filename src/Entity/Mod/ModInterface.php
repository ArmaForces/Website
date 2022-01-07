<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\SharedKernel\Domain\Model\BlamableEntityInterface;
use App\SharedKernel\Domain\Model\Traits\DescribedInterface;
use App\SharedKernel\Domain\Model\Traits\NamedInterface;

interface ModInterface extends BlamableEntityInterface, NamedInterface, DescribedInterface
{
    public function isSteamWorkshopMod(): bool;

    public function isDirectoryMod(): bool;

    public function getType(): ModTypeEnum;

    public function setType(ModTypeEnum $type): void;

    public function isTypeServerSide(): bool;

    public function isTypeRequired(): bool;

    public function isTypeOptional(): bool;

    public function isTypeClientSide(): bool;

    public function isUserSelectable(): bool;

    public function getStatus(): ?ModStatusEnum;

    public function setStatus(?ModStatusEnum $status): void;

    public function isStatusBroken(): bool;

    public function isStatusDisabled(): bool;
}
