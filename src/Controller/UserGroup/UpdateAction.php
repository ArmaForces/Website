<?php

declare(strict_types=1);

namespace App\Controller\UserGroup;

use App\Entity\UserGroup\UserGroup;
use App\Form\DataTransformerRegistry;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use App\Form\UserGroup\UserGroupFormType;
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
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/user-group/{id}/update', name: 'app_user_group_update')]
    #[IsGranted(PermissionsEnum::USER_GROUP_UPDATE->value, 'userGroup')]
    public function __invoke(Request $request, UserGroup $userGroup): Response
    {
        $userGroupFormDto = $this->dataTransformerRegistry->transformFromEntity(new UserGroupFormDto(), $userGroup);
        $form = $this->createForm(UserGroupFormType::class, $userGroupFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataTransformerRegistry->transformToEntity($userGroupFormDto, $userGroup);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_group_list');
        }

        return $this->render('user_group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
