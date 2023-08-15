<?php

declare(strict_types=1);

namespace App\Controller\Mod;

use App\Entity\Mod\AbstractMod;
use App\Form\DataTransformerRegistry;
use App\Form\Mod\Dto\ModFormDto;
use App\Form\Mod\ModFormType;
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

    #[Route('/mod/{id}/update', name: 'app_mod_update')]
    #[IsGranted(PermissionsEnum::MOD_UPDATE)]
    public function __invoke(Request $request, AbstractMod $mod): Response
    {
        $modFormDto = $this->dataTransformerRegistry->transformFromEntity(new ModFormDto(), $mod);
        $form = $this->createForm(ModFormType::class, $modFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedMod = $this->dataTransformerRegistry->transformToEntity($modFormDto, $mod);

            if (!$this->entityManager->contains($updatedMod)) {
                $this->entityManager->remove($mod);
                $this->entityManager->persist($updatedMod);
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list');
        }

        return $this->render('mod/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
