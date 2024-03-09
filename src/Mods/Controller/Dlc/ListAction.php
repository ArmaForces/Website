<?php

declare(strict_types=1);

namespace App\Mods\Controller\Dlc;

use App\Mods\Repository\Dlc\DlcRepository;
use App\Shared\Security\Enum\PermissionsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListAction extends AbstractController
{
    public function __construct(
        private DlcRepository $dlcRepository
    ) {
    }

    #[Route('/dlc/list', name: 'app_dlc_list')]
    #[IsGranted(PermissionsEnum::DLC_LIST->value)]
    public function __invoke(): Response
    {
        $dlcs = $this->dlcRepository->findBy([], ['name' => 'ASC']);

        return $this->render('mods/dlc/list.html.twig', [
            'dlcs' => $dlcs,
        ]);
    }
}
