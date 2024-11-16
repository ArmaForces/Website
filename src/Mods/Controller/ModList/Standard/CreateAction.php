<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList\Standard;

use App\Mods\Form\ModList\Standard\DataTransformer\StandardModListFormDtoDataTransformer;
use App\Mods\Form\ModList\Standard\Dto\StandardModListFormDto;
use App\Mods\Form\ModList\Standard\StandardModListFormType;
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
        private StandardModListFormDtoDataTransformer $standardModListFormDtoDataTransformer
    ) {
    }

    #[Route('/standard-mod-list/create', name: 'app_standard_mod_list_create')]
    #[IsGranted(PermissionsEnum::STANDARD_MOD_LIST_CREATE->value)]
    public function __invoke(Request $request): Response
    {
        $standardModListFormDto = $this->standardModListFormDtoDataTransformer->transformFromEntity(new StandardModListFormDto());
        $form = $this->createForm(StandardModListFormType::class, $standardModListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modList = $this->standardModListFormDtoDataTransformer->transformToEntity($standardModListFormDto);

            $this->entityManager->persist($modList);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mods/mod_list/standard_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
