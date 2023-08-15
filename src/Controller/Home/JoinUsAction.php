<?php

declare(strict_types=1);

namespace App\Controller\Home;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JoinUsAction extends AbstractController
{
    #[Route('/join-us', name: 'app_home_join_us')]
    public function __invoke(): Response
    {
        return $this->render('home/join_us/join_us.html.twig');
    }
}
