<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModListPublic;

use App\Mods\Entity\ModList\ModList;
use App\Mods\Repository\Mod\ModRepository;
use App\Shared\Security\Enum\PermissionsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function Symfony\Component\Clock\now;

class DownloadAction extends AbstractController
{
    public function __construct(
        private ModRepository $modRepository,
    ) {
    }

    #[Route('/mod-list/{name}/download/{optionalModsJson}', name: 'app_mod_list_public_download', options: ['expose' => true])]
    #[IsGranted(PermissionsEnum::MOD_LIST_DOWNLOAD->value, 'modList')]
    public function __invoke(ModList $modList, string $optionalModsJson = null): Response
    {
        $name = sprintf('ArmaForces %s %s', $modList->getName(), now()->format('Y_m_d H_i'));
        $mods = $this->modRepository->findIncludedSteamWorkshopMods($modList);
        $optionalMods = json_decode($optionalModsJson ?? '', true) ?: [];

        $template = $this->renderView('mods/mod_list_public/launcher_preset_template.html.twig', [
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
