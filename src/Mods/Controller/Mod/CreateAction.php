<?php

declare(strict_types=1);

namespace App\Mods\Controller\Mod;

use App\Mods\Form\Mod\DataTransformer\ModFormDtoDataTransformer;
use App\Mods\Form\Mod\Dto\ModFormDto;
use App\Mods\Form\Mod\ModFormType;
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
        private ModFormDtoDataTransformer $modFormDtoDataTransformer
    ) {
    }

    #[Route('/mod/create', name: 'app_mod_create')]
    #[IsGranted(PermissionsEnum::MOD_CREATE->value)]
    public function __invoke(Request $request): Response
    {
        $modFormDto = $this->modFormDtoDataTransformer->transformFromEntity(new ModFormDto());
        $form = $this->createForm(ModFormType::class, $modFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mod = $this->modFormDtoDataTransformer->transformToEntity($modFormDto);

            $this->entityManager->persist($mod);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list');
        }

        return $this->render('mods/mod/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
