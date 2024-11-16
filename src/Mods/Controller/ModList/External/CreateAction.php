<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList\External;

use App\Mods\Form\ModList\External\DataTransformer\ExternalModListFormDtoDataTransformer;
use App\Mods\Form\ModList\External\Dto\ExternalModListFormDto;
use App\Mods\Form\ModList\External\ExternalModListFormType;
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
        private ExternalModListFormDtoDataTransformer $externalModListFormDtoDataTransformer
    ) {
    }

    #[Route('/external-mod-list/create', name: 'app_external_mod_list_create')]
    #[IsGranted(PermissionsEnum::EXTERNAL_MOD_LIST_CREATE->value)]
    public function __invoke(Request $request): Response
    {
        $externalModListFormDto = $this->externalModListFormDtoDataTransformer->transformFromEntity(new ExternalModListFormDto());
        $form = $this->createForm(ExternalModListFormType::class, $externalModListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $externalModList = $this->externalModListFormDtoDataTransformer->transformToEntity($externalModListFormDto);

            $this->entityManager->persist($externalModList);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mods/mod_list/remote_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
