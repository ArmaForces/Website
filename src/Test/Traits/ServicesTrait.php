<?php

declare(strict_types=1);

namespace App\Test\Traits;

use App\Entity\EntityInterface;
use App\Entity\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Contracts\HttpClient\HttpClientInterface;

trait ServicesTrait
{
    private static HttpClientInterface|KernelBrowser $client;

    public static function getClient(): KernelBrowser|\ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client|HttpClientInterface
    {
        if (!self::$booted) {
            self::$client = self::createClient();
        }

        return self::$client;
    }

    /**
     * @see https://symfony.com/doc/4.4/testing/http_authentication.html
     */
    public static function authenticateClient(
        ?UserInterface $user = null,
        string $firewallName = 'main',
        string $firewallContext = null
    ) {
        $client = self::getClient();

        $session = self::getSession();
        $session->clear();

        if ($client instanceof KernelBrowser) {
            $client->getCookieJar()->clear();
        }

        if (!$user || $client instanceof HttpClientInterface) {
            return $client;
        }

        $token = new PostAuthenticationGuardToken($user, $firewallName, $user->getRoles());
        $firewallContext = $firewallContext ?? $firewallName;
        $session->set('_security_'.$firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());

        $client->getCookieJar()->set($cookie);

        return $client;
    }

    public static function getContainer(): ContainerInterface
    {
        if (!self::getContainer()) {
            self::getClient();
        }

        return self::getContainer();
    }

    public static function getSession(): SessionInterface
    {
        return self::getContainer()->get('session');
    }

    public static function getEntityManager(?string $name = null): EntityManagerInterface
    {
        return self::getContainer()->get('doctrine')->getManager($name);
    }

    public static function getEntityById(string $className, ?string $userId): ?EntityInterface
    {
        return self::getEntityManager()->getRepository($className)->find($userId);
    }
}
