<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ModList\ModList;
use App\Repository\ModListRepository;
use App\Repository\ModRepository;
use App\Security\Enum\PermissionsEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mod-list", name="app_mod_list_public")
 */
class ModListPublicController extends AbstractController
{
    /** @var ModRepository */
    protected $modRepository;

    /** @var ModListRepository */
    protected $modListRepository;

    public function __construct(ModRepository $modRepository, ModListRepository $modListRepository)
    {
        $this->modRepository = $modRepository;
        $this->modListRepository = $modListRepository;
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

        return $this->render('mod_list_public/select.html.twig', [
            'modLists' => $modLists,
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
        $template = $this->renderView('mod_list_public/launcher_preset_template.html.twig', [
            'modList' => $modList,
            'mods' => $this->modRepository->findIncludedSteamWorkshopMods($modList),
            'optionalMods' => $optionalModsJson ? json_decode($optionalModsJson, true) : [],
        ]);

        $response = new Response($template);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $modList->getName().'.html'
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
