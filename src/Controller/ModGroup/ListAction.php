<?php

declare(strict_types=1);

namespace App\Controller\ModGroup;

use App\Repository\ModGroup\ModGroupRepository;
use App\Security\Enum\PermissionsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListAction extends AbstractController
{
    public function __construct(
        private ModGroupRepository $modGroupRepository,
    ) {
    }

    #[Route('/mod-group/list', name: 'app_mod_group_list')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_LIST->value)]
    public function __invoke(): Response
    {
        $modGroups = $this->modGroupRepository->findBy([], ['name' => 'ASC']);

        return $this->render('mod_group/list.html.twig', [
            'modGroups' => $modGroups,
        ]);
    }
}