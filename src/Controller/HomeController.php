<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Mission\MissionClient;
use Psr\Log\LoggerInterface;
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

    /** @var LoggerInterface */
    protected $logger;

    public function __construct(MissionClient $missionClient, LoggerInterface $logger)
    {
        $this->missionClient = $missionClient;
        $this->logger = $logger;
    }

    /**
     * @Route("", name="_index")
     */
    public function indexAction(): Response
    {
        try {
            $nearestMission = $this->missionClient->getNearestMission();
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
            $openMissions = $this->missionClient->getMissions(false);
            $archivedMissions = $this->missionClient->getArchivedMissions();
        } catch (\Exception $ex) {
            $this->logger->warning('Could not fetch list of missions', ['ex' => $ex]);
            $openMissions = null;
            $archivedMissions = null;
        }

        return $this->render('home/missions/missions.html.twig', [
            'openMissions' => $openMissions,
            'archivedMissions' => $archivedMissions,
        ]);
    }
}
