<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Mod\Mod;
use App\Form\Mod\Dto\ModFormDto;
use App\Form\Mod\ModFormType;
use App\Repository\ModRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mod", name="app_mod")
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
     */
    public function updateAction(Request $request, Mod $mod): Response
    {
        $modFormDto = ModFormDto::fromEntity($mod);
        $form = $this->createForm(ModFormType::class, $modFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modFormDto->toEntity($mod);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list');
        }

        return $this->render('mod/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="_delete")
     */
    public function deleteAction(Mod $mod): Response
    {
        $this->entityManager->remove($mod);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list');
    }
}