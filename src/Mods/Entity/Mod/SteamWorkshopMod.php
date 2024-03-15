<?php

declare(strict_types=1);

namespace App\Mods\Entity\Mod;

use App\Mods\Entity\Mod\Enum\ModStatusEnum;
use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use Ramsey\Uuid\UuidInterface;

class SteamWorkshopMod extends AbstractMod
{
    private ModTypeEnum $type;
    private int $itemId;

    public function __construct(
        UuidInterface $id,
        string $name,
        ?string $description,
        ?ModStatusEnum $status,
        ModTypeEnum $type,
        int $itemId
    ) {
        parent::__construct($id, $name, $description, $status);

        $this->type = $type;
        $this->itemId = $itemId;
    }

    public function update(
        string $name,
        ?string $description,
        ?ModStatusEnum $status,
        ModTypeEnum $type,
        int $itemId
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->type = $type;
        $this->itemId = $itemId;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function getType(): ModTypeEnum
    {
        return $this->type;
    }
}
