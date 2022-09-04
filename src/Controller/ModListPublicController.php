<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ModList\ModList;
use App\Repository\Mod\ModRepository;
use App\Repository\ModList\ModListRepository;
use App\Security\Enum\PermissionsEnum;
use App\Service\Mission\MissionClient;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mod-list", name="app_mod_list_public")
 */
class ModListPublicController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger,
        private ModRepository $modRepository,
        private ModListRepository $modListRepository,
        private MissionClient $missionClient,
    ) {
    }

    /**
     * @Route("/select", name="_select")
     */
    public function selectAction(): Response
    {
        $modLists = $this->modListRepository->findBy(['active' => true], [
            'approved' => 'DESC',
            'name' => 'ASC',
        ]);

        try {
            $nextMission = $this->missionClient->getNextUpcomingMission();
        } catch (\Exception $ex) {
            $this->logger->warning('Could not fetch next upcoming', ['ex' => $ex]);
            $nextMission = null;
        }

        $nextMissionModlist = null;
        if ($nextMission) {
            $nextMissionModlist = $this->modListRepository->findOneBy(['name' => $nextMission->getModlist()]);
        }

        return $this->render('mod_list_public/select.html.twig', [
            'modLists' => $modLists,
            'nextMission' => $nextMission,
            'nextMissionModlist' => $nextMissionModlist,
        ]);
    }

    /**
     * @Route("/{name}", name="_customize")
     *
     * @IsGranted(PermissionsEnum::MOD_LIST_DOWNLOAD, subject="modList")
     */
    public function customizeAction(ModList $modList): Response
    {
        $optionalMods = $this->modRepository->findIncludedOptionalSteamWorkshopMods($modList);
        $requiredMods = $this->modRepository->findIncludedRequiredSteamWorkshopMods($modList);

        return $this->render('mod_list_public/customize.html.twig', [
            'modList' => $modList,
            'optionalMods' => $optionalMods,
            'requiredMods' => $requiredMods,
        ]);
    }

    /**
     * @Route("/{name}/download/{optionalModsJson}", name="_download", options={"expose": true})
     *
     * @IsGranted(PermissionsEnum::MOD_LIST_DOWNLOAD, subject="modList")
     */
    public function downloadAction(ModList $modList, string $optionalModsJson = null): Response
    {
        $name = sprintf('ArmaForces %s %s', $modList->getName(), (new \DateTimeImmutable())->format('Y_m_d H_i'));
        $mods = $this->modRepository->findIncludedSteamWorkshopMods($modList);
        $optionalMods = json_decode($optionalModsJson ?? '', true) ?: [];

        $template = $this->renderView('mod_list_public/launcher_preset_template.html.twig', [
            'name' => $name,
            'modList' => $modList,
            'mods' => $mods,
            'optionalMods' => $optionalMods,
        ]);

        return new Response($template, Response::HTTP_OK, [
            'Content-Disposition' => HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $name.'.html'
            ),
        ]);
    }
}
