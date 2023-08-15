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

class CopyAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/mod-list/{name}/copy', name: 'app_mod_list_copy')]
    #[IsGranted(PermissionsEnum::MOD_LIST_COPY)]
    public function __invoke(Request $request, ModList $modList): Response
    {
        /** @var ModListFormDto $modListFormDto */
        $modListFormDto = $this->dataTransformerRegistry->transformFromEntity(new ModListFormDto(), $modList);
        $modListFormDto->setId(null); // Entity will be treated as new by the unique name validator
        $modListFormDto->setApproved(false); // Reset approval status

        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modListCopy = $this->dataTransformerRegistry->transformToEntity($modListFormDto);

            $this->entityManager->persist($modListCopy);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mod_list/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
