<?php

declare(strict_types=1);

namespace App\Users\Controller\UserGroup;

use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Entity\UserGroup\UserGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DeleteAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/user-group/{name}/delete', name: 'app_user_group_delete')]
    #[IsGranted(PermissionsEnum::USER_GROUP_DELETE->value, 'userGroup')]
    public function __invoke(UserGroup $userGroup): Response
    {
        $this->entityManager->remove($userGroup);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_group_list');
    }
}
