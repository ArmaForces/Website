<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\UserEntity;
use App\Security\Exception\MultipleRolesFound;
use App\Security\Exception\RequiredRolesNotAssignedException;
use App\Security\Exception\RoleNotFoundException;
use App\Security\Exception\UserNotADiscordMemberException;
use App\Service\RestCord\DiscordClientFactory;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Token\AccessToken;
use RestCord\DiscordClient;
use RestCord\Model\Guild\Guild;
use RestCord\Model\Guild\GuildMember;
use RestCord\Model\Permissions\Role;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Wohali\OAuth2\Client\Provider\DiscordResourceOwner;

/**
 * @see https://github.com/knpuniversity/oauth2-client-bundle
 */
class DiscordAuthenticator extends SocialAuthenticator
{
    protected const DISCORD_CLIENT_NAME = 'discord_main';

    protected const HOME_INDEX_PAGE_ROUTE_NAME = 'app_home_index';
    protected const HOME_JOIN_US_PAGE_ROUTE_NAME = 'app_home_join_us';
    protected const LOGIN_PAGE_ROUTE_NAME = 'app_security_connect_discord';
    protected const SUPPORTED_ROUTE_NAME = 'app_security_connect_discord_check';

    /** @var ClientRegistry */
    protected $clientRegistry;

    /** @var EntityManagerInterface */
    protected $em;

    /** @var RouterInterface */
    protected $router;

    /** @var DiscordClientFactory */
    protected $discordClientFactory;

    /** @var ParameterBagInterface */
    protected $parameterBag;

    /** @var int */
    protected $discordServerId;

    /** @var string */
    protected $botToken;

    /** @var string */
    protected $recruitRoleName;

    /** @var string */
    protected $memberRoleName;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        RouterInterface $router,
        DiscordClientFactory $discordClientFactory,
        ParameterBagInterface $parameterBag,
        int $discordServerId,
        string $botToken,
        string $recruitRoleName,
        string $memberRoleName
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->discordClientFactory = $discordClientFactory;
        $this->parameterBag = $parameterBag;
        $this->discordServerId = $discordServerId;
        $this->botToken = $botToken;
        $this->recruitRoleName = $recruitRoleName;
        $this->memberRoleName = $memberRoleName;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return self::SUPPORTED_ROUTE_NAME === $request->attributes->get('_route');
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request): AccessToken
    {
        return $this->fetchAccessToken($this->getDiscordClient());
    }

    /**
     * @param AccessToken $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider): UserInterface
    {
        /** @var DiscordResourceOwner $discordResourceOwner */
        $discordResourceOwner = $this->getDiscordClient()->fetchUserFromToken($credentials);

        $userToken = $credentials->getToken();
        $discordClientAsUser = $this->discordClientFactory->createFromToken($userToken, DiscordClientFactory::TOKEN_TYPE_OAUTH);
        $this->verifyDiscordMembership($discordClientAsUser, $discordResourceOwner);

        $discordClientAsBot = $this->discordClientFactory->createFromToken($this->botToken, DiscordClientFactory::TOKEN_TYPE_BOT);
        $this->verifyDiscordRoleAssigned($discordClientAsBot, $discordResourceOwner);

        /** @var string $fullUsername */
        $fullUsername = $discordResourceOwner->getUsername().'#'.$discordResourceOwner->getDiscriminator();
        /** @var string $email */
        $email = $discordResourceOwner->getEmail();
        /** @var string $externalId */
        $externalId = $discordResourceOwner->getId();

        try {
            /** @var UserEntity $user */
            $user = $userProvider->loadUserByUsername($externalId);
            $user->setUsername($fullUsername);
            $user->setAvatarHash($discordResourceOwner->getAvatarHash());
        } catch (UsernameNotFoundException $ex) {
            $user = new UserEntity($fullUsername, $email, $externalId);
            $user->setAvatarHash($discordResourceOwner->getAvatarHash());
            $this->em->persist($user);
        }

        $this->em->flush();

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?RedirectResponse
    {
        $targetUrl = $this->router->generate(self::HOME_JOIN_US_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?RedirectResponse
    {
        $targetUrl = $this->router->generate(self::HOME_INDEX_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $targetUrl = $this->router->generate(self::LOGIN_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
    }

    protected function verifyDiscordMembership(DiscordClient $discordClient, DiscordResourceOwner $discordResourceOwner): void
    {
        $username = $discordResourceOwner->getUsername();
        $guilds = $discordClient->user->getCurrentUserGuilds([]);

        if (!$this->isMemberOfDiscordServer($guilds, $this->discordServerId)) {
            throw new UserNotADiscordMemberException(
                sprintf('User "%s" is not a member of ArmaForces Discord server!', $username)
            );
        }
    }

    protected function verifyDiscordRoleAssigned(DiscordClient $discordClient, DiscordResourceOwner $discordResourceOwner): void
    {
        $username = $discordResourceOwner->getUsername();
        $userId = (int) $discordResourceOwner->getId();
        $guildId = $this->discordServerId;

        $guildRoles = $discordClient->guild->getGuildRoles([
            'guild.id' => $guildId,
        ]);

        $userAsGuildMember = $discordClient->guild->getGuildMember([
            'guild.id' => $guildId,
            'user.id' => $userId,
        ]);

        $recruitRoleId = $this->getRoleIdByName($guildRoles, $this->recruitRoleName);
        $memberRoleId = $this->getRoleIdByName($guildRoles, $this->memberRoleName);

        if ($this->hasServerRole($userAsGuildMember, $recruitRoleId) || $this->hasServerRole($userAsGuildMember, $memberRoleId)) {
            return;
        }

        throw new RequiredRolesNotAssignedException(
            sprintf('User "%s" doesn\'t have required roles assigned!', $username)
        );
    }

    protected function getDiscordClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient(self::DISCORD_CLIENT_NAME);
    }

    /**
     * @param Role[] $roles
     */
    protected function getRoleIdByName(array $roles, string $roleName): int
    {
        $rolesIdsFound = [];

        foreach ($roles as $role) {
            if ($role->name === $roleName) {
                $rolesIdsFound[] = $role->id;
            }
        }

        switch (\count($rolesIdsFound)) {
            case 1:
                return $rolesIdsFound[0];
            case 0:
                throw new RoleNotFoundException(sprintf('Role "%s" wasn\'t not found!', $roleName));
            default:
                throw new MultipleRolesFound(sprintf('Multiple roles found by given name "%s"!', $roleName));
        }
    }

    /**
     * @param Guild[] $guilds
     */
    protected function isMemberOfDiscordServer(array $guilds, int $guildId): bool
    {
        foreach ($guilds as $guild) {
            if ($guild->id === $guildId) {
                return true;
            }
        }

        return false;
    }

    protected function hasServerRole(GuildMember $guildMember, int $roleId): bool
    {
        foreach ($guildMember->roles as $role) {
            if ($role === $roleId) {
                return true;
            }
        }

        return false;
    }
}
