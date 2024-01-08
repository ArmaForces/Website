<?php

declare(strict_types=1);

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class LogoutAction extends AbstractController
{
    #[Route('/security/logout', name: 'app_security_logout')]
    public function __invoke(): RedirectResponse
    {
        throw new \RuntimeException('This should never be executed!');
    }
}
