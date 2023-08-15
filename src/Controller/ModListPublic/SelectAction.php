<?php

declare(strict_types=1);

namespace App\Controller\ModListPublic;

use App\Repository\ModList\ModListRepository;
use App\Service\Mission\MissionClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SelectAction extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
        private ModListRepository $modListRepository,
        private MissionClientInterface $missionClient,
    ) {
    }

    #[Route('/mod-list/select', name: 'app_mod_list_public_select')]
    public function __invoke(): Response
    {
        $modLists = $this->modListRepository->findBy(['active' => true], [
            'approved' => 'DESC',
            'name' => 'ASC',
        ]);

        try {
            $nextMission = $this->missionClient->getCurrentMission();
        } catch (\Exception $ex) {
            $this->logger->warning('Could not fetch next upcoming', ['ex' => $ex]);
            $nextMission = null;
        }

        $nextMissionModList = null;
        if ($nextMission) {
            $nextMissionModList = $this->modListRepository->findOneBy(['name' => $nextMission->getModlist()]);
        }

        return $this->render('mod_list_public/select.html.twig', [
            'modLists' => $modLists,
            'nextMission' => $nextMission,
            'nextMissionModList' => $nextMissionModList,
        ]);
    }
}
