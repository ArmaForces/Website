<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Mission\MissionClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="app_home")
 */
class HomeController extends AbstractController
{
    public function __construct(
        private MissionClientInterface $missionClient,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @Route("", name="_index")
     */
    public function indexAction(): Response
    {
        try {
            $nearestMission = $this->missionClient->getNextUpcomingMission();
        } catch (\Exception $ex) {
            $this->logger->warning('Could not fetch nearest mission', ['ex' => $ex]);
            $nearestMission = null;
        }

        return $this->render('home/index/index.html.twig', [
            'nearestMission' => $nearestMission,
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
        try {
            $upcomingMissions = $this->missionClient->getUpcomingMissions();
            $archivedMissions = $this->missionClient->getArchivedMissions();
        } catch (\Exception $ex) {
            $this->logger->warning('Could not fetch list of missions', ['ex' => $ex]);
            $upcomingMissions = null;
            $archivedMissions = null;
        }

        return $this->render('home/missions/missions.html.twig', [
            'upcomingMissions' => $upcomingMissions,
            'archivedMissions' => $archivedMissions,
        ]);
    }
}
