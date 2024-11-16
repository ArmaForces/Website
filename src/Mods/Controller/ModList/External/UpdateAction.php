<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList\External;

use App\Mods\Entity\ModList\ExternalModList;
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

class UpdateAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExternalModListFormDtoDataTransformer $externalModListFormDtoDataTransformer
    ) {
    }

    #[Route('/external-mod-list/{name}/update', name: 'app_external_mod_list_update')]
    #[IsGranted(PermissionsEnum::EXTERNAL_MOD_LIST_UPDATE->value, 'externalModList')]
    public function __invoke(Request $request, ExternalModList $externalModList): Response
    {
        $externalModListFormDto = $this->externalModListFormDtoDataTransformer->transformFromEntity(new ExternalModListFormDto(), $externalModList);
        $form = $this->createForm(ExternalModListFormType::class, $externalModListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->externalModListFormDtoDataTransformer->transformToEntity($externalModListFormDto, $externalModList);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mods/mod_list/remote_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
