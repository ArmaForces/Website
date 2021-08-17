<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\BlamableEntityInterface;
use App\Entity\Permissions\UserPermissions;
use App\Entity\UserGroup\UserGroupInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserInterface extends BlamableEntityInterface, SymfonyUserInterface
{
    public function getUsername(): string;

    public function setUsername(string $username): void;

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getExternalId(): string;

    public function setExternalId(string $externalId): void;

    public function getPermissions(): UserPermissions;

    public function setPermissions(UserPermissions $permissions): void;

    public function addUserGroup(UserGroupInterface $userGroup): void;

    public function removeUserGroup(UserGroupInterface $userGroup): void;

    /**
     * @return UserGroupInterface[]
     */
    public function getUserGroups(): array;

    public function setUserGroups(array $userGroups): void;

    public function getAvatarHash(): ?string;

    public function setAvatarHash(?string $avatarHash): void;
}
