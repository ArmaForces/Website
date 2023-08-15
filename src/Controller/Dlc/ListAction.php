<?php

declare(strict_types=1);

namespace App\Controller\Dlc;

use App\Repository\Dlc\DlcRepository;
use App\Security\Enum\PermissionsEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListAction extends AbstractController
{
    public function __construct(
        private DlcRepository $dlcRepository
    ) {
    }

    #[Route('/dlc/list', name: 'app_dlc_list')]
    #[IsGranted(PermissionsEnum::DLC_LIST)]
    public function __invoke(): Response
    {
        $dlcs = $this->dlcRepository->findBy([], ['name' => 'ASC']);

        return $this->render('dlc/list.html.twig', [
            'dlcs' => $dlcs,
        ]);
    }
}
