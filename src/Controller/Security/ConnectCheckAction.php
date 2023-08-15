<?php

declare(strict_types=1);

namespace App\Controller\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class ConnectCheckAction extends AbstractController
{
    #[Route('/security/connect/discord/check', name: 'app_security_connect_discord_check')]
    public function __invoke(ClientRegistry $clientRegistry): RedirectResponse
    {
        throw new \RuntimeException('This should never be executed!');
    }
}
