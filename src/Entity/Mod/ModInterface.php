<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\EntityInterface;

interface ModInterface extends EntityInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getType(): string;

    public function setType(string $type): void;

    public function getSource(): string;

    public function setSource(string $source): void;

    public function getPath(): string;

    public function setPath(string $path): void;
}
