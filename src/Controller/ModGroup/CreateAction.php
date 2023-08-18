<?php

declare(strict_types=1);

namespace App\Controller\ModGroup;

use App\Form\DataTransformerRegistry;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use App\Form\ModGroup\ModGroupFormType;
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
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/mod-group/create', name: 'app_mod_group_create')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_CREATE)]
    public function __invoke(Request $request): Response
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
}
