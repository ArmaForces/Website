<?php

declare(strict_types=1);

namespace App\Shared\Controller\Home;

use App\Shared\Service\Mission\MissionClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MissionsAction extends AbstractController
{
    public function __construct(
        private MissionClientInterface $missionClient,
        private LoggerInterface $logger
    ) {
    }

    #[Route('/missions', name: 'app_home_missions')]
    public function __invoke(): Response
    {
        try {
            $upcomingMissions = $this->missionClient->getUpcomingMissions();
            $archivedMissions = $this->missionClient->getArchivedMissions();
        } catch (\Exception $ex) {
            $this->logger->warning('Could not fetch list of missions', ['ex' => $ex]);
            $upcomingMissions = null;
            $archivedMissions = null;
        }

        return $this->render('shared/home/missions/missions.html.twig', [
            'upcomingMissions' => $upcomingMissions,
            'archivedMissions' => $archivedMissions,
        ]);
    }
}
