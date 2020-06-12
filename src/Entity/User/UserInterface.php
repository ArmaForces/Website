<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\Permissions\Permissions;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserInterface extends SymfonyUserInterface
{
    public function getUsername(): string;

    public function setUsername(string $username): void;

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getExternalId(): string;

    public function setExternalId(string $externalId): void;

    public function getPermissions(): Permissions;

    public function setPermissions(Permissions $permissions): void;

    public function getAvatarHash(): ?string;

    public function setAvatarHash(?string $avatarHash): void;
}
