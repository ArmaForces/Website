<?php

declare(strict_types=1);

namespace App\Controller\ModList;

use App\Entity\ModList\ModList;
use App\Form\ModList\DataTransformer\ModListFormDtoDataTransformer;
use App\Form\ModList\Dto\ModListFormDto;
use App\Form\ModList\ModListFormType;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CopyAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ModListFormDtoDataTransformer $modListFormDtoDataTransformer
    ) {
    }

    #[Route('/mod-list/{name}/copy', name: 'app_mod_list_copy')]
    #[IsGranted(PermissionsEnum::MOD_LIST_COPY->value, 'modList')]
    public function __invoke(Request $request, ModList $modList): Response
    {
        $modListFormDto = $this->modListFormDtoDataTransformer->transformFromEntity(new ModListFormDto(), $modList, true);
        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modListCopy = $this->modListFormDtoDataTransformer->transformToEntity($modListFormDto);

            $this->entityManager->persist($modListCopy);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mod_list/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
