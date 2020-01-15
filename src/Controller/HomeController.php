<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Mission\MissionClient;
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
        $nearestMission = $this->missionClient->getNearestMission();
        $lastMission = null;

        if (null === $nearestMission) {
            foreach ($this->missionClient->getMissions(true) as $mission) {
                if ($mission->isArchived()) {
                    $lastMission = $mission;

                    break;
                }
            }
        }

        return $this->render('home/index/index.html.twig', [
            'upcomingMission' => $nearestMission,
            'lastMission' => $lastMission,
        ]);
    }

    /**
     * @Route("/join-us", name="_join_us")
     */
    public function joinUsAction(): Response
    {
        return $this->render('home/join_us/join_us.html.twig');
    }

    /**
     * @Route("/missions", name="_missions")
     */
    public function missionsAction(): Response
    {
        $missions = iterator_to_array($this->missionClient->getMissions(true));

        $firstArchivedIndex = -1;
        foreach ($missions as $idx => $mission) {
            if ($mission->isArchived()) {
                $firstArchivedIndex = $idx;

                break;
            }
        }

        return $this->render('home/missions/missions.html.twig', [
            'openMissions' => array_slice($missions, 0, $firstArchivedIndex),
            'archivedMissions' => array_slice($missions, $firstArchivedIndex),
        ]);
    }
}
