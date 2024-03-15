<?php

declare(strict_types=1);

namespace App\Mods\Controller\Mod;

use App\Mods\Repository\Mod\ModRepository;
use App\Shared\Security\Enum\PermissionsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListAction extends AbstractController
{
    public function __construct(
        private ModRepository $modRepository,
    ) {
    }

    #[Route('/mod/list', name: 'app_mod_list')]
    #[IsGranted(PermissionsEnum::MOD_LIST->value)]
    public function __invoke(): Response
    {
        $mods = $this->modRepository->findBy([], ['name' => 'ASC']);

        return $this->render('mods/mod/list.html.twig', [
            'mods' => $mods,
        ]);
    }
}
