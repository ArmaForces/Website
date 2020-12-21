<?php

declare(strict_types=1);

namespace App\Entity;

interface NamedEntityInterface extends EntityInterface
{
    public function getName(): string;

    public function setName(string $name): void;
}
