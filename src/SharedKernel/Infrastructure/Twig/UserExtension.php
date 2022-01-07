<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Twig;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    public const AVATAR_CDN = 'https://cdn.discordapp.com';
    public const DEFAULT_AVATAR_URL = '/embed/avatars/3.png';

    public function __construct(
        private Security $security
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('current_user_avatar_url', [$this, 'getCurrentUserAvatarUrl']),
            new TwigFunction('user_avatar_url', [$this, 'getUserAvatarUrl']),
            new TwigFunction('default_avatar_url', [$this, 'getDefaultAvatarUrl']),
        ];
    }

    public function getCurrentUserAvatarUrl(): string
    {
        /** @var null|User $user */
        $user = $this->security->getUser();
        if (!$user) {
            throw new \LogicException('Can\'t get user avatar url without user token!');
        }

        return $this->getUserAvatarUrl($user);
    }

    public function getUserAvatarUrl(User $user): string
    {
        $avatarHash = $user->getAvatarHash();

        if (null !== $avatarHash) {
            return sprintf('%s/avatars/%s/%s.png', self::AVATAR_CDN, $user->getExternalId(), $avatarHash);
        }

        return $this->getDefaultAvatarUrl();
    }

    public function getDefaultAvatarUrl(): string
    {
        return sprintf('%s%s', self::AVATAR_CDN, self::DEFAULT_AVATAR_URL);
    }
}
