<?php

declare(strict_types=1);

namespace App\Controller;

use App\Security\DiscordAuthenticator;
use App\Security\Enum\ScopeEnum;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/security", name="app_security")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/connect/discord", name="_connect_discord")
     */
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        // Prevent access for logged-in users
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home_index');
        }

        return $clientRegistry
            ->getClient('discord_main')
            ->redirect([
                ScopeEnum::IDENTIFY,
            ], [])
        ;
    }

    /**
     * @Route("/connect/discord/check", name="_connect_discord_check")
     *
     * @throws RuntimeException
     *
     * @see DiscordAuthenticator
     */
    public function connectCheckAction(): Response
    {
        throw new RuntimeException('This should never be executed!');
    }
}
