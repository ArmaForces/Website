<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList;

use App\Mods\Repository\ModList\ModListRepository;
use App\Shared\Security\Enum\PermissionsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListAction extends AbstractController
{
    public function __construct(
        private ModListRepository $modListRepository,
    ) {
    }

    #[Route('/mod-list/list', name: 'app_mod_list_list')]
    #[IsGranted(PermissionsEnum::MOD_LIST_LIST->value)]
    public function __invoke(): Response
    {
        $modLists = $this->modListRepository->findBy([], [
            'approved' => 'DESC',
            'name' => 'ASC',
        ]);

        return $this->render('mods/mod_list/list.html.twig', [
            'modLists' => $modLists,
        ]);
    }
}
