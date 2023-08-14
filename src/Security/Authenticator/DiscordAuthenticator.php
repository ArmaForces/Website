<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Entity\Permissions\UserPermissions;
use App\Entity\User\User;
use App\Security\Enum\ConnectionsEnum;
use App\Security\Exception\MultipleRolesFound;
use App\Security\Exception\RequiredRolesNotAssignedException;
use App\Security\Exception\RoleNotFoundException;
use App\Security\Exception\UserNotADiscordMemberException;
use App\Service\Discord\DiscordClientFactory;
use Discord\Http\Endpoint;
use Discord\Http\Exceptions\NotFoundException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Token\AccessToken;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Wohali\OAuth2\Client\Provider\DiscordResourceOwner;

use function React\Async\await;

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
        private string $discordServerId,
        private string $botToken,
        private array $requiredServerRoleNames
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): RedirectResponse
    {
        $targetUrl = $this->router->generate(self::LOGIN_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
    }

    public function supports(Request $request): bool
    {
        return self::SUPPORTED_ROUTE_NAME === $request->attributes->get('_route');
    }

    public function getCredentials(Request $request): AccessToken
    {
        return $this->fetchAccessToken($this->getDiscordClient());
    }

    /**
     * @param AccessToken $credentials
     */
    public function getUser($credentials, UserProviderInterface $userProvider): User
    {
        /** @var DiscordResourceOwner $discordResourceOwner */
        $discordResourceOwner = $this->getDiscordClient()->fetchUserFromToken($credentials);

        $userId = $discordResourceOwner->getId();
        $username = $discordResourceOwner->getUsername();
        $fullUsername = $discordResourceOwner->getUsername().'#'.$discordResourceOwner->getDiscriminator();
        $email = $discordResourceOwner->getEmail();
        $externalId = $discordResourceOwner->getId();

        $discordClientAsBot = $this->discordClientFactory->createBotClient($this->botToken);
        $discordClientAsUser = $this->discordClientFactory->createUserClient($credentials->getToken());

        $serverResponse = await($discordClientAsBot->get(
            Endpoint::bind(Endpoint::GUILD, $this->discordServerId)
        ));

        try {
            $userAsServerMemberResponse = await($discordClientAsBot->get(
                Endpoint::bind(Endpoint::GUILD_MEMBER, $serverResponse->id, $userId)
            ));
        } catch (NotFoundException $ex) {
            throw new UserNotADiscordMemberException(
                sprintf('User "%s" is not a member of "%s" Discord server!', $username, $serverResponse->name)
            );
        }

        // Collect IDs of required server roles by their names
        // This is needed as user object contains only IDs of roles assigned
        $serverRolesResponse = await($discordClientAsBot->get(
            Endpoint::bind(Endpoint::GUILD_ROLES, $serverResponse->id)
        ));
        $serverRoleIds = array_map(
            fn (string $requiredServerRoleName) => $this->getRoleIdByName($serverRolesResponse, $requiredServerRoleName),
            $this->requiredServerRoleNames
        );

        // Check if user has at least one role assigned
        if (0 === \count(array_intersect($serverRoleIds, $userAsServerMemberResponse->roles))) {
            throw new RequiredRolesNotAssignedException(
                sprintf('User "%s" does not have required roles assigned!', $username)
            );
        }

        $userConnectionsResponse = await($discordClientAsUser->get(
            Endpoint::bind(Endpoint::USER_CURRENT_CONNECTIONS)
        ));
        $steamConnection = (new ArrayCollection($userConnectionsResponse))
            ->filter(static fn (\stdClass $connection) => ConnectionsEnum::STEAM === $connection->type)
            ->first()
        ;

        $steamId = $steamConnection ? (int) $steamConnection->id : null;

        try {
            /** @var User $user */
            $user = $userProvider->loadUserByIdentifier($externalId);
            $user->update(
                $fullUsername,
                $email,
                $externalId,
                $user->getPermissions(),
                [],
                $discordResourceOwner->getAvatarHash(),
                $steamId
            );
        } catch (UserNotFoundException $ex) {
            $permissions = new UserPermissions(Uuid::uuid4());
            $user = new User(
                Uuid::uuid4(),
                $fullUsername,
                $email,
                $externalId,
                $permissions,
                [],
                $discordResourceOwner->getAvatarHash(),
                $steamId
            );

            $this->em->persist($permissions);
            $this->em->persist($user);
        }

        $this->em->flush();

        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?RedirectResponse
    {
        $targetUrl = $this->router->generate(self::HOME_JOIN_US_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?RedirectResponse
    {
        $targetUrl = $this->router->generate(self::HOME_INDEX_PAGE_ROUTE_NAME);

        return new RedirectResponse($targetUrl);
    }

    protected function getRoleIdByName(array $roles, string $roleName): string
    {
        $rolesFound = (new ArrayCollection($roles))->filter(static fn (\stdClass $role) => $role->name === $roleName);

        return match ($rolesFound->count()) {
            0 => throw new RoleNotFoundException(sprintf('Role "%s" was not found!', $roleName)),
            1 => $rolesFound->first()->id,
            default => throw new MultipleRolesFound(sprintf('Multiple roles found by given name "%s"!', $roleName))
        };
    }

    protected function getDiscordClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient(self::DISCORD_CLIENT_NAME);
    }
}
