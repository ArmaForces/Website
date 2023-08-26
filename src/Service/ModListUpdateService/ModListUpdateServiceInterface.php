<?php

declare(strict_types=1);

namespace App\Service\ModListUpdateService;

use App\Entity\ModGroup\ModGroupInterface;

interface ModListUpdateServiceInterface
{
    public function updateModListsAssociatedWithModGroup(ModGroupInterface $modGroup): void;
}
