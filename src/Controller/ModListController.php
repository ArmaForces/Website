<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ModList\ModList;
use App\Form\ModList\DataTransformer\ModListFormDtoDataTransformer;
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
 * @IsGranted("ROLE_USER")
 */
class ModListController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ModListRepository */
    protected $modListRepository;

    /** @var ModListFormDtoDataTransformer */
    protected $modListFormDtoDataTransformer;

    public function __construct(EntityManagerInterface $entityManager, ModListRepository $modListRepository, ModListFormDtoDataTransformer $modListFormDtoDataTransformer)
    {
        $this->entityManager = $entityManager;
        $this->modListRepository = $modListRepository;
        $this->modListFormDtoDataTransformer = $modListFormDtoDataTransformer;
    }

    /**
     * @Route("/list", name="_list")
     *
     * @IsGranted(PermissionsEnum::MOD_LIST_LIST)
     */
    public function listAction(): Response
    {
        $modLists = $this->modListRepository->findBy([], ['name' => 'ASC']);

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
            $modList = $this->modListFormDtoDataTransformer->toEntity($modListFormDto);
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
     * @IsGranted(PermissionsEnum::MOD_LIST_UPDATE, subject="modList")
     */
    public function updateAction(Request $request, ModList $modList): Response
    {
        $modListFormDto = $this->modListFormDtoDataTransformer->fromEntity($modList);
        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->modListFormDtoDataTransformer->toEntity($modListFormDto, $modList);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_list_list');
        }

        return $this->render('mod_list/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/copy", name="_copy")
     *
     * @IsGranted(PermissionsEnum::MOD_LIST_COPY)
     */
    public function copyAction(Request $request, ModList $modList): Response
    {
        $modListFormDto = $this->modListFormDtoDataTransformer->fromEntity($modList);
        $form = $this->createForm(ModListFormType::class, $modListFormDto);
        $modListFormDto->setId(null); // Entity will be treated as new by the unique name validator

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modListCopy = $this->modListFormDtoDataTransformer->toEntity($modListFormDto);

            $this->entityManager->persist($modListCopy);
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
     * @IsGranted(PermissionsEnum::MOD_LIST_DELETE, subject="modList")
     */
    public function deleteAction(ModList $modList): Response
    {
        $this->entityManager->remove($modList);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list_list');
    }
}
