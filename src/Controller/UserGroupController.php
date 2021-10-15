<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserGroup\UserGroup;
use App\Form\DataTransformerRegistry;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use App\Form\UserGroup\UserGroupFormType;
use App\Repository\UserGroup\UserGroupRepository;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user-group", name="app_user_group")
 *
 * @IsGranted("ROLE_USER")
 */
class UserGroupController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserGroupRepository $userGroupRepository,
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    /**
     * @Route("/list", name="_list")
     *
     * @IsGranted(PermissionsEnum::USER_GROUP_LIST)
     */
    public function listAction(): Response
    {
        $userGroups = $this->userGroupRepository->findBy([], ['name' => 'ASC']);

        return $this->render('user_group/list.html.twig', [
            'userGroups' => $userGroups,
        ]);
    }

    /**
     * @Route("/create", name="_create")
     *
     * @IsGranted(PermissionsEnum::USER_GROUP_CREATE)
     */
    public function createAction(Request $request): Response
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

    /**
     * @Route("/{name}/update", name="_update")
     *
     * @IsGranted(PermissionsEnum::USER_GROUP_UPDATE, subject="userGroup")
     */
    public function updateAction(Request $request, UserGroup $userGroup): Response
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

    /**
     * @Route("/{name}/delete", name="_delete")
     *
     * @IsGranted(PermissionsEnum::USER_GROUP_DELETE, subject="userGroup")
     */
    public function deleteAction(UserGroup $userGroup): Response
    {
        $this->entityManager->remove($userGroup);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_user_group_list');
    }
}
