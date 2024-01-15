<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User\User;
use App\Form\User\DataTransformer\UserFormDtoDataTransformer;
use App\Form\User\Dto\UserFormDto;
use App\Form\User\UserFormType;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UpdateAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserFormDtoDataTransformer $userFormDtoDataTransformer
    ) {
    }

    #[Route('/user/{id}/update', name: 'app_user_update')]
    #[IsGranted(PermissionsEnum::USER_UPDATE->value, 'user')]
    public function __invoke(Request $request, User $user): Response
    {
        $userFormDto = $this->userFormDtoDataTransformer->transformFromEntity(new UserFormDto(), $user);
        $form = $this->createForm(UserFormType::class, $userFormDto, [
            'target' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userFormDtoDataTransformer->transformToEntity($userFormDto, $user);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render('user/form.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
