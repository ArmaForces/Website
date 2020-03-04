<?php

declare(strict_types=1);

namespace App\Entity;

class AbstractNamedEntity extends AbstractEntity implements NamedEntityInterface
{
    /** @var string */
    protected $name;

    public function __construct(string $name)
    {
        parent::__construct();

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
