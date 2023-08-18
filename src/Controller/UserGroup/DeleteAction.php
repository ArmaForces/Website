<?php

declare(strict_types=1);

namespace App\Controller\UserGroup;

use App\Entity\UserGroup\UserGroup;
use App\Security\Enum\PermissionsEnum;
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

    #[Route('/user-group/{id}/delete', name: 'app_user_group_delete')]
    #[IsGranted(PermissionsEnum::USER_GROUP_DELETE, 'userGroup')]
    public function __invoke(UserGroup $userGroup): Response
    {
        $this->entityManager->remove($userGroup);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_group_list');
    }
}
