<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList;

use App\Mods\Form\ModList\DataTransformer\ModListFormDtoDataTransformer;
use App\Mods\Form\ModList\Dto\ModListFormDto;
use App\Mods\Form\ModList\ModListFormType;
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
        private ModListFormDtoDataTransformer $modListFormDtoDataTransformer
    ) {
    }

    #[Route('/mod-list/create', name: 'app_mod_list_create')]
    #[IsGranted(PermissionsEnum::MOD_LIST_CREATE->value)]
    public function __invoke(Request $request): Response
    {
        $modListFormDto = $this->modListFormDtoDataTransformer->transformFromEntity(new ModListFormDto());
        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modList = $this->modListFormDtoDataTransformer->transformToEntity($modListFormDto);

            $this->entityManager->persist($modList);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mods/mod_list/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
