<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User\UserEntity;
use App\Repository\UserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="app_user")
 */
class UserController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var UserEntityRepository */
    protected $userEntityRepository;

    public function __construct(EntityManagerInterface $entityManager, UserEntityRepository $userEntityRepository)
    {
        $this->entityManager = $entityManager;
        $this->userEntityRepository = $userEntityRepository;
    }

    /**
     * @Route("/list", name="_list")
     */
    public function listAction(): Response
    {
        $users = $this->userEntityRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="_delete")
     */
    public function deleteAction(UserEntity $userEntity): Response
    {
        $this->entityManager->remove($userEntity);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_list');
    }
}
