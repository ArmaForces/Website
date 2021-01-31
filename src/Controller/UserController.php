<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User\User;
use App\Form\Permissions\PermissionsType;
use App\Repository\User\UserRepository;
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
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var UserRepository */
    protected $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/list", name="_list")
     *
     * @IsGranted(PermissionsEnum::USER_LIST)
     */
    public function listAction(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="_delete")
     *
     * @IsGranted(PermissionsEnum::USER_DELETE, subject="user")
     */
    public function deleteAction(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_list');
    }

    /**
     * @Route("/{id}/permissions", name="_permissions")
     *
     * @IsGranted(PermissionsEnum::USER_PERMISSIONS_MANAGE, subject="user")
     */
    public function permissionsAction(Request $request, User $user): Response
    {
        $permissions = $user->getPermissions();
        $form = $this->createForm(PermissionsType::class, $permissions, [
            'target' => $user,
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Manually mark User entity as changed
            $this->entityManager->getUnitOfWork()->scheduleForUpdate($user);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/permissions.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
