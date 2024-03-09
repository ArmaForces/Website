<?php

declare(strict_types=1);

namespace App\Users\Controller\UserGroup;

use App\Shared\Security\Enum\PermissionsEnum;
use App\Users\Entity\UserGroup\UserGroup;
use App\Users\Form\UserGroup\DataTransformer\UserGroupFormDtoDataTransformer;
use App\Users\Form\UserGroup\Dto\UserGroupFormDto;
use App\Users\Form\UserGroup\UserGroupFormType;
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
        private UserGroupFormDtoDataTransformer $userGroupFormDtoDataTransformer
    ) {
    }

    #[Route('/user-group/{name}/update', name: 'app_user_group_update')]
    #[IsGranted(PermissionsEnum::USER_GROUP_UPDATE->value, 'userGroup')]
    public function __invoke(Request $request, UserGroup $userGroup): Response
    {
        $userGroupFormDto = $this->userGroupFormDtoDataTransformer->transformFromEntity(new UserGroupFormDto(), $userGroup);
        $form = $this->createForm(UserGroupFormType::class, $userGroupFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userGroupFormDtoDataTransformer->transformToEntity($userGroupFormDto, $userGroup);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_user_group_list');
        }

        return $this->render('users/user_group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
