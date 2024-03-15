<?php

declare(strict_types=1);

namespace App\Mods\Controller\Dlc;

use App\Mods\Form\Dlc\DataTransformer\DlcFormDtoDataTransformer;
use App\Mods\Form\Dlc\DlcFormType;
use App\Mods\Form\Dlc\Dto\DlcFormDto;
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
        private DlcFormDtoDataTransformer $dlcFormDtoDataTransformer
    ) {
    }

    #[Route('/dlc/create', name: 'app_dlc_create')]
    #[IsGranted(PermissionsEnum::DLC_CREATE->value)]
    public function __invoke(Request $request): Response
    {
        $dlcFormDto = $this->dlcFormDtoDataTransformer->transformFromEntity(new DlcFormDto());
        $form = $this->createForm(DlcFormType::class, $dlcFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dlc = $this->dlcFormDtoDataTransformer->transformToEntity($dlcFormDto);

            $this->entityManager->persist($dlc);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_dlc_list');
        }

        return $this->render('mods/dlc/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
