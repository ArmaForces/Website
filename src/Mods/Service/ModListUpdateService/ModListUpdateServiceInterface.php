<?php

declare(strict_types=1);

namespace App\Mods\Service\ModListUpdateService;

use App\Mods\Entity\ModGroup\ModGroup;

interface ModListUpdateServiceInterface
{
    public function updateModListsAssociatedWithModGroup(ModGroup $modGroup): void;
}
