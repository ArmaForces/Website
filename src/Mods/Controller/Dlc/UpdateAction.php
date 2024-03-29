<?php

declare(strict_types=1);

namespace App\Mods\Controller\Dlc;

use App\Mods\Entity\Dlc\Dlc;
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

class UpdateAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DlcFormDtoDataTransformer $dlcFormDtoDataTransformer
    ) {
    }

    #[Route('/dlc/{id}/update', name: 'app_dlc_update')]
    #[IsGranted(PermissionsEnum::DLC_UPDATE->value, 'dlc')]
    public function __invoke(Request $request, Dlc $dlc): Response
    {
        $dlcFormDto = $this->dlcFormDtoDataTransformer->transformFromEntity(new DlcFormDto(), $dlc);
        $form = $this->createForm(DlcFormType::class, $dlcFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dlcFormDtoDataTransformer->transformToEntity($dlcFormDto, $dlc);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_dlc_list');
        }

        return $this->render('mods/dlc/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
