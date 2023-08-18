<?php

declare(strict_types=1);

namespace App\Controller\ModList;

use App\Entity\ModList\ModList;
use App\Form\DataTransformerRegistry;
use App\Form\ModList\Dto\ModListFormDto;
use App\Form\ModList\ModListFormType;
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

    #[Route('/mod-list/{name}/update', name: 'app_mod_list_update')]
    #[IsGranted(PermissionsEnum::MOD_LIST_UPDATE, 'modList')]
    public function __invoke(Request $request, ModList $modList): Response
    {
        $modListFormDto = $this->dataTransformerRegistry->transformFromEntity(new ModListFormDto(), $modList);
        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataTransformerRegistry->transformToEntity($modListFormDto, $modList);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mod_list/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
