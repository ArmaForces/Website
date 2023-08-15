<?php

declare(strict_types=1);

namespace App\Controller\UserGroup;

use App\Form\DataTransformerRegistry;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use App\Form\UserGroup\UserGroupFormType;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/user-group/create', name: 'app_user_group_create')]
    #[IsGranted(PermissionsEnum::USER_GROUP_CREATE)]
    public function __invoke(Request $request): Response
    {
        $userGroupFormDto = new UserGroupFormDto();
        $form = $this->createForm(UserGroupFormType::class, $userGroupFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userGroup = $this->dataTransformerRegistry->transformToEntity($userGroupFormDto);

            $this->entityManager->persist($userGroup);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_group_list');
        }

        return $this->render('user_group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
