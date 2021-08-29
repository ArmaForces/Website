<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User\User;
use App\Form\DataTransformerRegistry;
use App\Form\User\Dto\UserFormDto;
use App\Form\User\UserFormType;
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
    protected EntityManagerInterface $entityManager;
    protected UserRepository $userRepository;
    protected DataTransformerRegistry $dataTransformerRegistry;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        DataTransformerRegistry $dataTransformerRegistry
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->dataTransformerRegistry = $dataTransformerRegistry;
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
     * @Route("/{id}/update", name="_update")
     *
     * @IsGranted(PermissionsEnum::USER_UPDATE, subject="user")
     */
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
}
