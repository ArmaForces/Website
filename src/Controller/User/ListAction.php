<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Repository\User\UserRepository;
use App\Security\Enum\PermissionsEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ListAction extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    #[Route('/user/list', name: 'app_user_list')]
    #[IsGranted(PermissionsEnum::USER_LIST->value)]
    public function __invoke(): Response
    {
        $users = $this->userRepository->findBy([], ['username' => 'ASC']);

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }
}
