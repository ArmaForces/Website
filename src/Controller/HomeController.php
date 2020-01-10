<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Mission\MissionClient;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="app_home")
 */
class HomeController extends AbstractController
{
    /** @var MissionClient */
    protected $missionClient;

    public function __construct(MissionClient $missionClient)
    {
        $this->missionClient = $missionClient;
    }

    /**
     * @Route("", name="_index")
     */
    public function indexAction(): Response
    {
        $recentMissions = $this->missionClient->getMissions();

        return $this->render('home/index/index.html.twig', [
            'recentMissions' => $recentMissions,
        ]);
    }

    /**
     * @Route("/join-us", name="_join_us")
     */
    public function joinUsAction(): Response
    {
        return $this->render('home/join_us/join_us.html.twig');
    }
}
