<?php

declare(strict_types=1);

namespace App\Twig;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public const AVATAR_CDN = 'https://cdn.discordapp.com';
    public const DEFAULT_AVATAR_URL = '/embed/avatars/3.png';

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_user_avatar_url', [$this, 'getCurrentUserAvatarUrl']),
            new TwigFunction('user_avatar_url', [$this, 'getUserAvatarUrl']),
        ];
    }

    public function getCurrentUserAvatarUrl(): string
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            throw new \LogicException('Can\'t get user avatar url without user token!');
        }

        /** @var User $user */
        $user = $token->getUser();

        return $this->getUserAvatarUrl($user);
    }

    public function getUserAvatarUrl(User $user): string
    {
        $avatarHash = $user->getAvatarHash();

        if (null !== $avatarHash) {
            return sprintf('%s/avatars/%s/%s.png', self::AVATAR_CDN, $user->getExternalId(), $avatarHash);
        }

        return sprintf('%s/%s', self::AVATAR_CDN, self::DEFAULT_AVATAR_URL);
    }
}
