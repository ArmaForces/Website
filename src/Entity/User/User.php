<?php

declare(strict_types=1);

namespace App\Entity\User;

class User extends AbstractUser
{
    /** @var string */
    protected $email;

    /** @var string */
    protected $externalId;

    /** @var null|string */
    protected $avatarHash;

    public function __construct(string $externalId, string $username, string $email)
    {
        parent::__construct();

        $this->externalId = $externalId;
        $this->username = $username;
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): void
    {
        $this->externalId = $externalId;
    }

    public function getAvatarHash(): ?string
    {
        return $this->avatarHash;
    }

    public function setAvatarHash(?string $avatarHash): void
    {
        $this->avatarHash = $avatarHash;
    }
}
