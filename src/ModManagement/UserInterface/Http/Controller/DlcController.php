<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Http\Controller;

use App\ModManagement\Domain\Model\Dlc\Dlc;
use App\ModManagement\Infrastructure\Persistence\Dlc\DlcRepository;
use App\ModManagement\UserInterface\Http\Form\Dlc\DlcFormType;
use App\ModManagement\UserInterface\Http\Form\Dlc\Dto\DlcFormDto;
use App\SharedKernel\Infrastructure\Security\Enum\PermissionsEnum;
use App\SharedKernel\Infrastructure\Security\Enum\RoleEnum;
use App\SharedKernel\UserInterface\Http\Form\DataTransformerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route('/dlc', name: 'app_dlc')]
#[IsGranted(RoleEnum::ROLE_USER)]
class DlcController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DlcRepository $dlcRepository,
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/list', name: '_list')]
    #[IsGranted(PermissionsEnum::DLC_LIST)]
    public function listAction(): Response
    {
        $dlcs = $this->dlcRepository->findBy([], ['name' => 'ASC']);

        return $this->render('dlc/list.html.twig', [
            'dlcs' => $dlcs,
        ]);
    }

    #[Route('/create', name: '_create')]
    #[IsGranted(PermissionsEnum::DLC_CREATE)]
    public function createAction(Request $request): Response
    {
        $dlcFormDto = new DlcFormDto();
        $form = $this->createForm(DlcFormType::class, $dlcFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dlc = $this->dataTransformerRegistry->transformToEntity($dlcFormDto);

            $this->entityManager->persist($dlc);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_dlc_list');
        }

        return $this->render('dlc/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/update', name: '_update')]
    #[IsGranted(PermissionsEnum::DLC_UPDATE, subject: 'dlc')]
    public function updateAction(Request $request, Dlc $dlc): Response
    {
        $dlcFormDto = $this->dataTransformerRegistry->transformFromEntity(new DlcFormDto(), $dlc);
        $form = $this->createForm(DlcFormType::class, $dlcFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataTransformerRegistry->transformToEntity($dlcFormDto, $dlc);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_dlc_list');
        }

        return $this->render('dlc/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: '_delete')]
    #[IsGranted(PermissionsEnum::DLC_DELETE, subject: 'dlc')]
    public function deleteAction(Dlc $dlc): Response
    {
        $this->entityManager->remove($dlc);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_dlc_list');
    }
}
