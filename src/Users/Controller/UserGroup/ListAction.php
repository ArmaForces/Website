<?php

declare(strict_types=1);

namespace App\Users\Controller\UserGroup;

use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Repository\UserGroup\UserGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListAction extends AbstractController
{
    public function __construct(
        private UserGroupRepository $userGroupRepository
    ) {
    }

    #[Route('/user-group/list', name: 'app_user_group_list')]
    #[IsGranted(PermissionsEnum::USER_GROUP_LIST->value)]
    public function __invoke(): Response
    {
        $userGroups = $this->userGroupRepository->findBy([], ['name' => 'ASC']);

        return $this->render('users/user_group/list.html.twig', [
            'userGroups' => $userGroups,
        ]);
    }
}
