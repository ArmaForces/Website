<?php

declare(strict_types=1);

namespace App\Users\Controller\User;

use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Entity\User\User;
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

    #[Route('/user/{id}/delete', name: 'app_user_delete')]
    #[IsGranted(PermissionsEnum::USER_DELETE->value, 'user')]
    public function __invoke(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_list');
    }
}
