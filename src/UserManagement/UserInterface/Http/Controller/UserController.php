<?php

declare(strict_types=1);

namespace App\UserManagement\UserInterface\Http\Controller;

use App\SharedKernel\Infrastructure\Security\Enum\PermissionsEnum;
use App\SharedKernel\Infrastructure\Security\Enum\RoleEnum;
use App\SharedKernel\UserInterface\Http\Form\DataTransformerRegistry;
use App\UserManagement\Domain\Model\User\User;
use App\UserManagement\Infrastructure\Persistence\User\UserRepository;
use App\UserManagement\UserInterface\Http\Form\User\Dto\UserFormDto;
use App\UserManagement\UserInterface\Http\Form\User\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/user', name: 'app_user')]
#[IsGranted(RoleEnum::ROLE_USER)]
class UserController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/list', name: '_list')]
    #[IsGranted(PermissionsEnum::USER_LIST)]
    public function listAction(): Response
    {
        $users = $this->userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}/update', name: '_update')]
    #[IsGranted(PermissionsEnum::USER_UPDATE, subject: 'user')]
    public function updateAction(Request $request, User $user): Response
    {
        $userFormDto = $this->dataTransformerRegistry->transformFromEntity(new UserFormDto(), $user);
        $form = $this->createForm(UserFormType::class, $userFormDto, [
            'target' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataTransformerRegistry->transformToEntity($userFormDto, $user);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/form.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: '_delete')]
    #[IsGranted(PermissionsEnum::USER_DELETE, subject: 'user')]
    public function deleteAction(User $user): Response
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_list');
    }
}
