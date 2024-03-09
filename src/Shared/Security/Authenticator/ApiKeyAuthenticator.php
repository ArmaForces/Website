<?php

declare(strict_types=1);

namespace App\Shared\Security\Authenticator;

use App\Shared\Security\Model\ApiTokenUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private string $apiKeyHeaderName,
        private string $apiAllowedKeys
    ) {
    }

    public function supports(Request $request): ?bool
    {
        return true;
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get($this->apiKeyHeaderName, '');
        $allowedTokens = explode(',', $this->apiAllowedKeys);
        $allowedTokens = array_map('trim', $allowedTokens);
        $allowedTokens = array_filter($allowedTokens, static fn (string $allowedToken) => !empty($allowedToken));

        if (!\in_array($token, $allowedTokens, true)) {
            throw new AccessDeniedHttpException('Invalid or missing API key provided!');
        }

        return new SelfValidatingPassport(new UserBadge($token, fn () => new ApiTokenUser($token)));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }
}
