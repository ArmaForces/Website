<?php

declare(strict_types=1);

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="app_home")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", name="_index")
     */
    public function indexAction(): Response
    {
        $recentMissions = [];
        for ($i = 1; $i < 7; ++$i) {
            $recentMissions[] = [
                'title' => "Mission {$i}",
                'description' => 'Lorem ipsum, ArmaForces karamba.',
                'date' => new DateTime("-{$i} day"),
            ];
        }

        return $this->render('index.html.twig', [
            'recentMissions' => $recentMissions,
        ]);
    }

    /**
     * @Route("/join-us", name="_join_us")
     */
    public function joinUs()
    {
        return $this->render('join_us.html.twig');
    }
}
