<?php

declare(strict_types=1);

namespace App\Service\ModListUpdateService;

use App\Entity\ModGroup\ModGroup;

interface ModListUpdateServiceInterface
{
    public function updateModListsAssociatedWithModGroup(ModGroup $modGroup): void;
}
