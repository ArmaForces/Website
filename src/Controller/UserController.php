<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User\UserEntity;
use App\Form\Permissions\PermissionsType;
use App\Repository\UserEntityRepository;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="app_user")
 *
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
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
     *
     * @IsGranted(PermissionsEnum::LIST_USERS)
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
     *
     * @IsGranted(PermissionsEnum::DELETE_USERS, subject="userEntity")
     */
    public function deleteAction(UserEntity $userEntity): Response
    {
        /** @var UserEntity $currentUser */
        $currentUser = $this->getUser();

        $this->entityManager->remove($userEntity);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_list');
    }

    /**
     * @Route("/{id}/permissions", name="_permissions")
     *
     * @IsGranted(PermissionsEnum::MANAGE_USERS_PERMISSIONS, subject="userEntity")
     */
    public function permissionsAction(Request $request, UserEntity $userEntity): Response
    {
        $permissionsEntity = $userEntity->getPermissions();
        $form = $this->createForm(PermissionsType::class, $permissionsEntity, [
            'relatedUser' => $userEntity,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/permissions.html.twig', [
            'user' => $userEntity,
            'form' => $form->createView(),
        ]);
    }
}
