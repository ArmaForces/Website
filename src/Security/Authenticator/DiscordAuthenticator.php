<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Entity\Permissions\UserPermissions;
use App\Entity\User\User;
use App\Entity\User\UserInterface;
use App\Security\Enum\ConnectionsEnum;
use App\Security\Exception\MultipleRolesFound;
use App\Security\Exception\RequiredRolesNotAssignedException;
use App\Security\Exception\RoleNotFoundException;
use App\Security\Exception\UserNotADiscordMemberException;
use App\Service\RestCord\DiscordClientFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Command\Exception\CommandException;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Token\AccessToken;
use Ramsey\Uuid\Uuid;
use RestCord\Model\Permissions\Role;
use RestCord\Model\User\Connection;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
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

    public function __construct(
        private ClientRegistry $clientRegistry,
        private EntityManagerInterface $em,
        private RouterInterface $router,
        private DiscordClientFactory $discordClientFactory,
        private int $discordServerId,
        private string $botToken,
        private array $requiredServerRoleNames
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $targetUrl = $this->router->generate(self::LOGIN_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
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

        $userId = (int) $discordResourceOwner->getId();
        $username = $discordResourceOwner->getUsername();
        $fullUsername = $discordResourceOwner->getUsername().'#'.$discordResourceOwner->getDiscriminator();
        $email = $discordResourceOwner->getEmail();
        $externalId = $discordResourceOwner->getId();

        $discordClientAsBot = $this->discordClientFactory->createBotClient($this->botToken);
        $discordClientAsUser = $this->discordClientFactory->createUserClient($credentials->getToken());

        $server = $discordClientAsBot->guild->getGuild(['guild.id' => $this->discordServerId]);

        try {
            $userAsServerMember = $discordClientAsBot->guild->getGuildMember([
                'guild.id' => $server->id,
                'user.id' => $userId,
            ]);
        } catch (CommandException $ex) {
            throw new UserNotADiscordMemberException(
                sprintf('User "%s" is not a member of "%s" Discord server!', $username, $server->name)
            );
        }

        // Collect IDs of required server roles by their names
        // This is needed as user object contains only IDs of roles assigned
        $serverRoleIds = [];
        $serverRoles = $discordClientAsBot->guild->getGuildRoles(['guild.id' => $server->id]);
        foreach ($this->requiredServerRoleNames as $requiredServerRoleName) {
            $serverRoleIds[] = $this->getRoleByName($serverRoles, $requiredServerRoleName)->id;
        }

        // Check if user has at least one role assigned
        if (0 === \count(array_intersect($serverRoleIds, $userAsServerMember->roles))) {
            throw new RequiredRolesNotAssignedException(
                sprintf('User "%s" does not have required roles assigned!', $username)
            );
        }

        $userConnections = $discordClientAsUser->user->getUserConnections([]);
        $steamConnection = (new ArrayCollection($userConnections))
            ->filter(static fn (Connection $connection) => ConnectionsEnum::STEAM === $connection->type)
            ->first()
        ;

        $steamId = $steamConnection ? (int) $steamConnection->id : null;

        try {
            /** @var User $user */
            $user = $userProvider->loadUserByUsername($externalId);
            $user->setUsername($fullUsername);
            $user->setEmail($email);
        } catch (UsernameNotFoundException $ex) {
            $permissions = new UserPermissions(Uuid::uuid4());
            $user = new User(Uuid::uuid4(), $fullUsername, $email, $externalId, $permissions);

            $this->em->persist($permissions);
            $this->em->persist($user);
        }

        $user->setAvatarHash($discordResourceOwner->getAvatarHash());
        $user->setSteamId($steamId);

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
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?RedirectResponse
    {
        $targetUrl = $this->router->generate(self::HOME_INDEX_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
    }

    protected function getRoleByName(array $roles, string $roleName): Role
    {
        $rolesFound = (new ArrayCollection($roles))->filter(static fn (Role $role) => $role->name === $roleName);

        switch ($rolesFound->count()) {
            case 0:
                throw new RoleNotFoundException(sprintf('Role "%s" was not found!', $roleName));

            case 1:
                return $rolesFound->first();

            default:
                throw new MultipleRolesFound(sprintf('Multiple roles found by given name "%s"!', $roleName));
        }
    }

    protected function getDiscordClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient(self::DISCORD_CLIENT_NAME);
    }
}
