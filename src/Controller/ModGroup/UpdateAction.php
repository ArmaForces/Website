<?php

declare(strict_types=1);

namespace App\Controller\ModGroup;

use App\Entity\ModGroup\ModGroup;
use App\Form\DataTransformerRegistry;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use App\Form\ModGroup\ModGroupFormType;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/mod-group/{name}/update', name: 'app_mod_group_update')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_UPDATE, 'modGroup')]
    public function __invoke(Request $request, ModGroup $modGroup): Response
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
}
