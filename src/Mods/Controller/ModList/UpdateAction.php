<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList;

use App\Mods\Entity\ModList\ModList;
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

class UpdateAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ModListFormDtoDataTransformer $modListFormDtoDataTransformer
    ) {
    }

    #[Route('/mod-list/{name}/update', name: 'app_mod_list_update')]
    #[IsGranted(PermissionsEnum::MOD_LIST_UPDATE->value, 'modList')]
    public function __invoke(Request $request, ModList $modList): Response
    {
        $modListFormDto = $this->modListFormDtoDataTransformer->transformFromEntity(new ModListFormDto(), $modList);
        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->modListFormDtoDataTransformer->transformToEntity($modListFormDto, $modList);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mods/mod_list/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
