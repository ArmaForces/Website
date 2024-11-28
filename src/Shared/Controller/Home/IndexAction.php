<?php

declare(strict_types=1);

namespace App\Shared\Controller\Home;

use App\Shared\Service\Mission\MissionClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexAction extends AbstractController
{
    public function __construct(
        private MissionClientInterface $missionClient,
        private LoggerInterface $logger
    ) {
    }

    #[Route('/', name: 'app_home_index')]
    public function __invoke(): Response
    {
        try {
            $nearestMission = $this->missionClient->getNextUpcomingMission();
        } catch (\Exception $ex) {
            $this->logger->warning('Could not fetch nearest mission', ['ex' => $ex]);
            $nearestMission = null;
        }

        return $this->render('shared/home/index/index.html.twig', [
            'nearestMission' => $nearestMission,
        ]);
    }
}
