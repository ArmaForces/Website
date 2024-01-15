<?php

declare(strict_types=1);

namespace App\Controller\Mod;

use App\Form\Mod\DataTransformer\ModFormDtoDataTransformer;
use App\Form\Mod\Dto\ModFormDto;
use App\Form\Mod\ModFormType;
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
        private ModFormDtoDataTransformer $modFormDtoDataTransformer
    ) {
    }

    #[Route('/mod/create', name: 'app_mod_create')]
    #[IsGranted(PermissionsEnum::MOD_CREATE->value)]
    public function __invoke(Request $request): Response
    {
        $modFormDto = new ModFormDto();
        $form = $this->createForm(ModFormType::class, $modFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mod = $this->modFormDtoDataTransformer->transformToEntity($modFormDto);

            $this->entityManager->persist($mod);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list');
        }

        return $this->render('mod/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
