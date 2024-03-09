<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModGroup;

use App\Mods\Form\ModGroup\DataTransformer\ModGroupFormDtoDataTransformer;
use App\Mods\Form\ModGroup\Dto\ModGroupFormDto;
use App\Mods\Form\ModGroup\ModGroupFormType;
use App\Shared\Security\Enum\PermissionsEnum;
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
        private ModGroupFormDtoDataTransformer $modGroupFormDtoDataTransformer
    ) {
    }

    #[Route('/mod-group/create', name: 'app_mod_group_create')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_CREATE->value)]
    public function __invoke(Request $request): Response
    {
        $modGroupFormDto = $this->modGroupFormDtoDataTransformer->transformFromEntity(new ModGroupFormDto());
        $form = $this->createForm(ModGroupFormType::class, $modGroupFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modGroup = $this->modGroupFormDtoDataTransformer->transformToEntity($modGroupFormDto);

            $this->entityManager->persist($modGroup);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_group_list');
        }

        return $this->render('mods/mod_group/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
