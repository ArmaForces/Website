<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ModTag\ModTag;
use App\Form\DataTransformerRegistry;
use App\Form\ModTag\Dto\ModTagFormDto;
use App\Form\ModTag\ModTagFormType;
use App\Repository\ModTag\ModTagRepository;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mod-tag", name="app_mod_tag")
 *
 * @IsGranted("ROLE_USER")
 */
class ModTagController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ModTagRepository */
    protected $modTagRepository;

    /** @var DataTransformerRegistry */
    protected $dataTransformerRegistry;

    public function __construct(
        EntityManagerInterface $entityManager,
        ModTagRepository $modTagRepository,
        DataTransformerRegistry $dataTransformerRegistry
    ) {
        $this->entityManager = $entityManager;
        $this->modTagRepository = $modTagRepository;
        $this->dataTransformerRegistry = $dataTransformerRegistry;
    }

    /**
     * @Route("/list", name="_list")
     *
     * @IsGranted(PermissionsEnum::MOD_TAG_LIST)
     */
    public function listAction(): Response
    {
        $modTags = $this->modTagRepository->findBy([], ['name' => 'ASC']);

        return $this->render('mod_tag/list.html.twig', [
            'modTags' => $modTags,
        ]);
    }

    /**
     * @Route("/create", name="_create")
     *
     * @IsGranted(PermissionsEnum::MOD_TAG_CREATE)
     */
    public function createAction(Request $request): Response
    {
        $modTagFormDto = new ModTagFormDto();
        $form = $this->createForm(ModTagFormType::class, $modTagFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modTag = $this->dataTransformerRegistry->transformToEntity($modTagFormDto);

            $this->entityManager->persist($modTag);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_tag_list');
        }

        return $this->render('mod_tag/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}/update", name="_update")
     *
     * @IsGranted(PermissionsEnum::MOD_TAG_UPDATE, subject="modTag")
     */
    public function updateAction(Request $request, ModTag $modTag): Response
    {
        $modTagFormDto = $this->dataTransformerRegistry->transformFromEntity(new ModTagFormDto(), $modTag);
        $form = $this->createForm(ModTagFormType::class, $modTagFormDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataTransformerRegistry->transformToEntity($modTagFormDto, $modTag);

            $this->entityManager->flush();

            return $this->redirectToRoute('app_mod_tag_list');
        }

        return $this->render('mod_tag/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{name}/delete", name="_delete")
     *
     * @IsGranted(PermissionsEnum::MOD_TAG_DELETE, subject="modTag")
     */
    public function deleteAction(ModTag $modTag): Response
    {
        $this->entityManager->remove($modTag);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_tag_list');
    }
}
