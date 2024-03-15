<?php

declare(strict_types=1);

namespace App\Service\IdentifierFactory;

use Ramsey\Uuid\UuidInterface;

class IdentifierFactoryStub implements IdentifierFactoryInterface
{
    /**
     * @param UuidInterface[] $identifiers
     */
    private array $identifiers = [];

    /**
     * @param UuidInterface[] $identifiers
     */
    public function setIdentifiers(array $identifiers): void
    {
        foreach ($identifiers as $identifier) {
            $this->addIdentifier($identifier);
        }
    }

    public function create(): UuidInterface
    {
        if (!$this->identifiers) {
            throw new \LogicException('No identifiers provided');
        }

        $key = array_key_first($this->identifiers);
        $value = $this->identifiers[$key];
        unset($this->identifiers[$key]);

        return $value;
    }

    private function addIdentifier(UuidInterface $identifier): void
    {
        if (\in_array($identifier, $this->identifiers, true)) {
            throw new \LogicException('Non unique identifier provided');
        }

        $this->identifiers[] = $identifier;
    }
}
