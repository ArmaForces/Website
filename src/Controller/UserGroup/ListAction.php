<?php

declare(strict_types=1);

namespace App\Controller\UserGroup;

use App\Repository\UserGroup\UserGroupRepository;
use App\Security\Enum\PermissionsEnum;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListAction extends AbstractController
{
    public function __construct(
        private UserGroupRepository $userGroupRepository
    ) {
    }

    #[Route('/user-group/list', name: 'app_user_group_list')]
    #[IsGranted(PermissionsEnum::USER_GROUP_LIST)]
    public function __invoke(): Response
    {
        $userGroups = $this->userGroupRepository->findBy([], ['name' => 'ASC']);

        return $this->render('user_group/list.html.twig', [
            'userGroups' => $userGroups,
        ]);
    }
}
