<?php

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;

class AbstractNamedEntity extends AbstractEntity implements NamedEntityInterface
{
    /** @var string */
    protected $name;

    public function __construct(UuidInterface $id, string $name)
    {
        parent::__construct($id);

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
