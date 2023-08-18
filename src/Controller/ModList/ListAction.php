<?php

declare(strict_types=1);

namespace App\Controller\ModList;

use App\Repository\ModList\ModListRepository;
use App\Security\Enum\PermissionsEnum;
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
    #[IsGranted(PermissionsEnum::MOD_LIST_LIST)]
    public function __invoke(): Response
    {
        $modLists = $this->modListRepository->findBy([], [
            'approved' => 'DESC',
            'name' => 'ASC',
        ]);

        return $this->render('mod_list/list.html.twig', [
            'modLists' => $modLists,
        ]);
    }
}
