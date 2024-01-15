<?php

declare(strict_types=1);

namespace App\Controller\UserGroup;

use App\Form\UserGroup\DataTransformer\UserGroupFormDtoDataTransformer;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use App\Form\UserGroup\UserGroupFormType;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CreateAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserGroupFormDtoDataTransformer $userGroupFormDtoDataTransformer
    ) {
    }

    #[Route('/user-group/create', name: 'app_user_group_create')]
    #[IsGranted(PermissionsEnum::USER_GROUP_CREATE->value)]
    public function __invoke(Request $request): Response
    {
        $userGroupFormDto = new UserGroupFormDto();
        $form = $this->createForm(UserGroupFormType::class, $userGroupFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userGroup = $this->userGroupFormDtoDataTransformer->transformToEntity($userGroupFormDto);

            $this->entityManager->persist($userGroup);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_group_list');
        }

        return $this->render('user_group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
