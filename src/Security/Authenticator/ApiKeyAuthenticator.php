<?php

declare(strict_types=1);

namespace App\Security\Authenticator;

use App\Security\Model\ApiTokenUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiKeyAuthenticator extends AbstractGuardAuthenticator
{
    public function __construct(
        private string $apiKeyHeaderName,
        private string $apiAllowedKeys
    ) {
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new Response(null, 401);
    }

    public function supports(Request $request): bool
    {
        return true;
    }

    public function getCredentials(Request $request): string
    {
        return $request->headers->get($this->apiKeyHeaderName, '');
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        return new ApiTokenUser($credentials);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $allowedTokens = explode(',', $this->apiAllowedKeys);
        $allowedTokens = array_map('trim', $allowedTokens);
        $allowedTokens = array_filter($allowedTokens, static fn (string $allowedToken) => !empty($allowedToken));

        return \in_array($credentials, $allowedTokens, true);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw new AccessDeniedHttpException('Invalid or missing API key provided!');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }
}
