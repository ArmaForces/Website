<?php

declare(strict_types=1);

namespace App\Controller\ModListPublic;

use App\Entity\ModList\ModList;
use App\Repository\Mod\ModRepository;
use App\Security\Enum\PermissionsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CustomizeAction extends AbstractController
{
    public function __construct(
        private ModRepository $modRepository,
    ) {
    }

    #[Route('/mod-list/{name}', name: 'app_mod_list_public_customize', priority: -1)]
    #[IsGranted(PermissionsEnum::MOD_LIST_DOWNLOAD, 'modList')]
    public function __invoke(ModList $modList): Response
    {
        $optionalMods = $this->modRepository->findIncludedOptionalSteamWorkshopMods($modList);
        $requiredMods = $this->modRepository->findIncludedRequiredSteamWorkshopMods($modList);

        return $this->render('mod_list_public/customize.html.twig', [
            'modList' => $modList,
            'optionalMods' => $optionalMods,
            'requiredMods' => $requiredMods,
        ]);
    }
}
