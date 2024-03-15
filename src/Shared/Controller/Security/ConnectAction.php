<?php

declare(strict_types=1);

namespace App\Shared\Controller\Security;

use App\Shared\Security\Enum\ScopeEnum;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class ConnectAction extends AbstractController
{
    #[Route('/security/connect/discord', name: 'app_security_connect_discord')]
    public function __invoke(ClientRegistry $clientRegistry): RedirectResponse
    {
        // Prevent access for logged-in users
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home_index');
        }

        return $clientRegistry
            ->getClient('discord_main')
            ->redirect([
                ScopeEnum::IDENTIFY->value,
                ScopeEnum::EMAIL->value,
                ScopeEnum::GUILDS->value,
                ScopeEnum::CONNECTIONS->value,
            ], [])
        ;
    }
}
