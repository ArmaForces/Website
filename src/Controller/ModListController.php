<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ModList\ModList;
use App\Form\ModList\Dto\ModListFormDto;
use App\Form\ModList\ModListFormType;
use App\Repository\ModListRepository;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mod-list", name="app_mod_list")
 *
 * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
 */
class ModListController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ModListRepository */
    protected $modListRepository;

    public function __construct(EntityManagerInterface $entityManager, ModListRepository $modListRepository)
    {
        $this->entityManager = $entityManager;
        $this->modListRepository = $modListRepository;
    }

    /**
     * @Route("/list", name="_list")
     *
     * @IsGranted(PermissionsEnum::MOD_LIST_LIST)
     */
    public function listAction(): Response
    {
        $modLists = $this->modListRepository->findAll();

        return $this->render('mod_list/list.html.twig', [
            'modLists' => $modLists,
        ]);
    }

    /**
     * @Route("/create", name="_create")
     *
     * @IsGranted(PermissionsEnum::MOD_LIST_CREATE)
     */
    public function createAction(Request $request): Response
    {
        $modListFormDto = new ModListFormDto();
        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modList = $modListFormDto->toEntity();
            $this->entityManager->persist($modList);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mod_list/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/update", name="_update")
     *
     * @IsGranted(PermissionsEnum::MOD_LIST_UPDATE)
     */
    public function updateAction(Request $request, ModList $modList): Response
    {
        $modListFormDto = ModListFormDto::fromEntity($modList);
        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modListFormDto->toEntity($modList);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mod_list/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="_delete")
     *
     * @IsGranted(PermissionsEnum::MOD_LIST_DELETE)
     */
    public function deleteAction(ModList $modList): Response
    {
        $this->entityManager->remove($modList);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list_list');
    }
}
