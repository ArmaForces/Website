<?php

declare(strict_types=1);

namespace App\SharedKernel\UserInterface\Http\Controller;

use App\SharedKernel\Infrastructure\Service\Mission\MissionClient;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/', name: 'app_home')]
class HomeController extends AbstractController
{
    public function __construct(
        private MissionClient $missionClient,
        private LoggerInterface $logger
    ) {
    }

    #[Route(path: '', name: '_index')]
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

    #[Route(path: '/join-us', name: '_join_us')]
    public function joinUsAction(): Response
    {
        return $this->render('home/join_us/join_us.html.twig');
    }

    #[Route(path: '/missions', name: '_missions')]
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
