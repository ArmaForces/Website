<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\User;
use App\Exception\MissingConfigParameterValueException;
use App\Security\Exception\RequiredRoleNotAssignedException;
use App\Security\Exception\RoleNotFoundException;
use App\Security\Exception\UserNotADiscordMemberException;
use App\Service\RestCord\DiscordClientFactory;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Token\AccessToken;
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

    protected const PARAMETER_DISCORD_SERVER_ID_KEY = 'app.security.oauth.discord.server_id';
    protected const PARAMETER_DISCORD_SERVER_MEMBER_ROLE = 'app.security.oauth.discord.member_role';
    protected const PARAMETER_DISCORD_BOT_TOKEN_KEY = 'app.security.oauth.discord.bot_token';

    protected const HOME_PAGE_ROUTE_NAME = 'app_home_index';
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

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        RouterInterface $router,
        DiscordClientFactory $discordClientFactory,
        ParameterBagInterface $parameterBag
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->discordClientFactory = $discordClientFactory;
        $this->parameterBag = $parameterBag;
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
        /** @var DiscordResourceOwner $discordUser */
        $discordUser = $this->getDiscordClient()->fetchUserFromToken($credentials);
        $userId = (int) $discordUser->getId();
        $username = $discordUser->getUsername();
        $guildId = (int) $this->getParameterValue(self::PARAMETER_DISCORD_SERVER_ID_KEY);

        $userToken = $credentials->getToken();
        $discordClientOAuth = $this->discordClientFactory->createFromToken($userToken, DiscordClientFactory::TOKEN_TYPE_OAUTH);

        // Check if user joined Discord server
        $guilds = $discordClientOAuth->user->getCurrentUserGuilds([]);
        if (!$this->isMemberOfDiscordServer($guilds, $guildId)) {
            throw new UserNotADiscordMemberException('User is not a member of ArmaForces Discord server!');
        }

        $botToken = $this->getParameterValue(self::PARAMETER_DISCORD_BOT_TOKEN_KEY);
        $discordClientBot = $this->discordClientFactory->createFromToken($botToken, DiscordClientFactory::TOKEN_TYPE_BOT);

        // Check if user was approved (has needed Discord server role)
        $guildRoles = $discordClientBot->guild->getGuildRoles([
            'guild.id' => $guildId,
        ]);

        $requiredRoleName = $this->getParameterValue(self::PARAMETER_DISCORD_SERVER_MEMBER_ROLE);
        $requiredRoleId = $this->getRoleIdByName($guildRoles, $requiredRoleName);

        $userAsGuildMember = $discordClientBot->guild->getGuildMember([
            'guild.id' => $guildId,
            'user.id' => $userId,
        ]);

        if (!$this->hasServerRole($userAsGuildMember, $requiredRoleId)) {
            throw new RequiredRoleNotAssignedException(
                sprintf('User "%s" doesn\'t have required role "%s" assigned!', $username, $requiredRoleName)
            );
        }

        /** @var string $email */
        $email = $discordUser->getEmail();

        try {
            $user = $userProvider->loadUserByUsername($email);
        } catch (UsernameNotFoundException $ex) {
            $user = new User($email, $email);
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
        $targetUrl = $this->router->generate(self::HOME_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?RedirectResponse
    {
        $targetUrl = $this->router->generate(self::HOME_PAGE_ROUTE_NAME);

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

    public function getParameterValue(string $key): string
    {
        $value = $this->parameterBag->get($key);
        if (!$key) {
            throw new MissingConfigParameterValueException($key);
        }

        return $value;
    }

    protected function getDiscordClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient(self::DISCORD_CLIENT_NAME);
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

    /**
     * @param Role[] $roles
     */
    protected function getRoleIdByName(array $roles, string $roleName): int
    {
        foreach ($roles as $role) {
            if ($role->name === $roleName) {
                return $role->id;
            }
        }

        throw new RoleNotFoundException(
            sprintf('Role "%s" wasn\'t not found!', $roleName)
        );
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
