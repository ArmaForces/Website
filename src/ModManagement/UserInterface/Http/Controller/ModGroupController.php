<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Http\Controller;

use App\ModManagement\Domain\Model\ModGroup\ModGroup;
use App\ModManagement\Infrastructure\Persistence\ModGroup\ModGroupRepository;
use App\ModManagement\UserInterface\Http\Form\ModGroup\Dto\ModGroupFormDto;
use App\ModManagement\UserInterface\Http\Form\ModGroup\ModGroupFormType;
use App\SharedKernel\Infrastructure\Security\Enum\PermissionsEnum;
use App\SharedKernel\Infrastructure\Security\Enum\RoleEnum;
use App\SharedKernel\UserInterface\Http\Form\DataTransformerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/mod-group', name: 'app_mod_group')]
#[IsGranted(RoleEnum::ROLE_USER)]
class ModGroupController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ModGroupRepository $modGroupRepository,
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/list', name: '_list')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_LIST)]
    public function listAction(): Response
    {
        $modGroups = $this->modGroupRepository->findBy([], ['name' => 'ASC']);

        return $this->render('mod_group/list.html.twig', [
            'modGroups' => $modGroups,
        ]);
    }

    #[Route('/create', name: '_create')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_CREATE)]
    public function createAction(Request $request): Response
    {
        $modGroupFormDto = new ModGroupFormDto();
        $form = $this->createForm(ModGroupFormType::class, $modGroupFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modGroup = $this->dataTransformerRegistry->transformToEntity($modGroupFormDto);

            $this->entityManager->persist($modGroup);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_group_list');
        }

        return $this->render('mod_group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{name}/update', name: '_update')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_UPDATE, subject: 'modGroup')]
    public function updateAction(Request $request, ModGroup $modGroup): Response
    {
        $modGroupFormDto = $this->dataTransformerRegistry->transformFromEntity(new ModGroupFormDto(), $modGroup);
        $form = $this->createForm(ModGroupFormType::class, $modGroupFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataTransformerRegistry->transformToEntity($modGroupFormDto, $modGroup);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_group_list');
        }

        return $this->render('mod_group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{name}/delete', name: '_delete')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_DELETE, subject: 'modGroup')]
    public function deleteAction(ModGroup $modGroup): Response
    {
        $this->entityManager->remove($modGroup);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_group_list');
    }
}
