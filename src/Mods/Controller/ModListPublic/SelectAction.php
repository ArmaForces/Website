<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModListPublic;

use App\Mods\Query\ModList\ActiveModListListQuery;
use App\Mods\Repository\ModList\ModListRepository;
use App\Shared\Service\Mission\MissionClientInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SelectAction extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
        private ModListRepository $modListRepository,
        private ActiveModListListQuery $activeModListListQuery,
        private MissionClientInterface $missionClient,
    ) {
    }

    #[Route('/mod-list/select', name: 'app_mod_list_public_select')]
    public function __invoke(): Response
    {
        $modLists = $this->activeModListListQuery->getResult();

        try {
            $nextMission = $this->missionClient->getCurrentMission();
        } catch (\Exception $ex) {
            $this->logger->warning('Could not fetch next upcoming', ['ex' => $ex]);
            $nextMission = null;
        }

        $nextMissionModList = null;
        if ($nextMission) {
            $nextMissionModList = $this->modListRepository->findOneByName($nextMission->getModlist());
        }

        return $this->render('mods/mod_list_public/select.html.twig', [
            'modLists' => $modLists,
            'nextMission' => $nextMission,
            'nextMissionModList' => $nextMissionModList,
        ]);
    }
}
