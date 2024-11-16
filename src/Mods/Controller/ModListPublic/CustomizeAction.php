<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModListPublic;

use App\Mods\Entity\ModList\AbstractModList;
use App\Mods\Entity\ModList\ExternalModList;
use App\Mods\Entity\ModList\StandardModList;
use App\Mods\Repository\Mod\ModRepository;
use App\Shared\Security\Enum\PermissionsEnum;
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
    #[IsGranted(PermissionsEnum::MOD_LIST_DOWNLOAD->value, 'modList')]
    public function __invoke(AbstractModList $modList): Response
    {
        /** @var ExternalModList|StandardModList $modList */
        if ($modList instanceof ExternalModList) {
            return $this->redirect($modList->getUrl());
        }

        $optionalMods = $this->modRepository->findIncludedOptionalSteamWorkshopMods($modList);
        $requiredMods = $this->modRepository->findIncludedRequiredSteamWorkshopMods($modList);

        return $this->render('mods/mod_list_public/customize.html.twig', [
            'modList' => $modList,
            'optionalMods' => $optionalMods,
            'requiredMods' => $requiredMods,
        ]);
    }
}
