<?php

declare(strict_types=1);

namespace App\Controller\ModListPublic;

use App\Entity\ModList\ModList;
use App\Repository\Mod\ModRepository;
use App\Security\Enum\PermissionsEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadAction extends AbstractController
{
    public function __construct(
        private ModRepository $modRepository,
    ) {
    }

    #[Route('/mod-list/{name}/download/{optionalModsJson}', name: 'app_mod_list_public_download', options: ['expose' => true])]
    #[IsGranted(PermissionsEnum::MOD_LIST_DOWNLOAD, 'modList')]
    public function __invoke(ModList $modList, string $optionalModsJson = null): Response
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
