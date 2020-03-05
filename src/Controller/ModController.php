<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Mod\AbstractMod;
use App\Form\Mod\Dto\ModFormDto;
use App\Form\Mod\ModFormType;
use App\Repository\ModRepository;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mod", name="app_mod")
 *
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class ModController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ModRepository */
    protected $modRepository;

    public function __construct(EntityManagerInterface $entityManager, ModRepository $modRepository)
    {
        $this->entityManager = $entityManager;
        $this->modRepository = $modRepository;
    }

    /**
     * @Route("/list", name="_list")
     *
     * @IsGranted(PermissionsEnum::MODS_LIST)
     */
    public function listAction(): Response
    {
        $mods = $this->modRepository->findAll();

        return $this->render('mod/list.html.twig', [
            'mods' => $mods,
        ]);
    }

    /**
     * @Route("/create", name="_create")
     *
     * @IsGranted(PermissionsEnum::MODS_CREATE)
     */
    public function createAction(Request $request): Response
    {
        $modFormDto = new ModFormDto();
        $form = $this->createForm(ModFormType::class, $modFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mod = $modFormDto->toEntity();
            $this->entityManager->persist($mod);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list');
        }

        return $this->render('mod/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/update", name="_update")
     *
     * @IsGranted(PermissionsEnum::MODS_UPDATE)
     */
    public function updateAction(Request $request, AbstractMod $mod): Response
    {
        $modFormDto = ModFormDto::fromEntity($mod);
        $form = $this->createForm(ModFormType::class, $modFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updatedMod = $modFormDto->toEntity($mod);

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

    /**
     * @Route("/{id}/delete", name="_delete")
     *
     * @IsGranted(PermissionsEnum::MODS_DELETE)
     */
    public function deleteAction(AbstractMod $mod): Response
    {
        $this->entityManager->remove($mod);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list');
    }
}
