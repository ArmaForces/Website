<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Http\Controller;

use App\ModManagement\Domain\Model\Mod\AbstractMod;
use App\ModManagement\Infrastructure\Persistence\Mod\ModRepository;
use App\ModManagement\UserInterface\Http\Form\Mod\Dto\ModFormDto;
use App\ModManagement\UserInterface\Http\Form\Mod\ModFormType;
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
#[Route('/mod', name: 'app_mod')]
#[IsGranted(RoleEnum::ROLE_USER)]
class ModController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ModRepository $modRepository,
        private DataTransformerRegistry $dataTransformerRegistry
    ) {
    }

    #[Route('/list', name: '_list')]
    #[IsGranted(PermissionsEnum::MOD_LIST)]
    public function listAction(): Response
    {
        $mods = $this->modRepository->findBy([], ['name' => 'ASC']);

        return $this->render('mod/list.html.twig', [
            'mods' => $mods,
        ]);
    }

    #[Route('/create', name: '_create')]
    #[IsGranted(PermissionsEnum::MOD_CREATE)]
    public function createAction(Request $request): Response
    {
        $modFormDto = new ModFormDto();
        $form = $this->createForm(ModFormType::class, $modFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mod = $this->dataTransformerRegistry->transformToEntity($modFormDto);

            $this->entityManager->persist($mod);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list');
        }

        return $this->render('mod/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/update', name: '_update')]
    #[IsGranted(PermissionsEnum::MOD_UPDATE, subject: 'mod')]
    public function updateAction(Request $request, AbstractMod $mod): Response
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

    #[Route('/{id}/delete', name: '_delete')]
    #[IsGranted(PermissionsEnum::MOD_DELETE, subject: 'mod')]
    public function deleteAction(AbstractMod $mod): Response
    {
        $this->entityManager->remove($mod);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list');
    }
}
