<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList\Common;

use App\Mods\Query\ModList\ModListListQuery;
use App\Shared\Security\Enum\PermissionsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListAction extends AbstractController
{
    public function __construct(
        private ModListListQuery $modListListQuery,
    ) {
    }

    #[Route('/mod-list/list', name: 'app_mod_list_list')]
    #[IsGranted(PermissionsEnum::MOD_LIST_LIST->value)]
    public function __invoke(): Response
    {
        $modLists = $this->modListListQuery->getResult();

        return $this->render('mods/mod_list/list.html.twig', [
            'modLists' => $modLists,
        ]);
    }
}
