<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Mod\AbstractMod;
use App\Form\DataTransformerRegistry;
use App\Form\Mod\Dto\ModFormDto;
use App\Form\Mod\ModFormType;
use App\Repository\ModGroupRepository;
use App\Repository\ModListRepository;
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
 * @IsGranted("ROLE_USER")
 */
class ModController extends AbstractController
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ModRepository */
    protected $modRepository;

    /** @var ModGroupRepository */
    protected $modGroupRepository;

    /** @var ModListRepository */
    protected $modListRepository;

    /** @var DataTransformerRegistry */
    protected $dataTransformerRegistry;

    public function __construct(
        EntityManagerInterface $entityManager,
        ModRepository $modRepository,
        ModGroupRepository $modGroupRepository,
        ModListRepository $modListRepository,
        DataTransformerRegistry $dataTransformerRegistry
    ) {
        $this->entityManager = $entityManager;
        $this->modRepository = $modRepository;
        $this->modGroupRepository = $modGroupRepository;
        $this->modListRepository = $modListRepository;
        $this->dataTransformerRegistry = $dataTransformerRegistry;
    }

    /**
     * @Route("/list", name="_list")
     *
     * @IsGranted(PermissionsEnum::MOD_LIST)
     */
    public function listAction(): Response
    {
        $mods = $this->modRepository->findBy([], ['name' => 'ASC']);

        return $this->render('mod/list.html.twig', [
            'mods' => $mods,
        ]);
    }

    /**
     * @Route("/create", name="_create")
     *
     * @IsGranted(PermissionsEnum::MOD_CREATE)
     */
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

    /**
     * @Route("/{id}/update", name="_update")
     *
     * @IsGranted(PermissionsEnum::MOD_UPDATE, subject="mod")
     */
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

    /**
     * @Route("/{id}/delete", name="_delete")
     *
     * @IsGranted(PermissionsEnum::MOD_DELETE, subject="mod")
     */
    public function deleteAction(AbstractMod $mod): Response
    {
        foreach ($this->modGroupRepository->findAll() as $modGroup) {
            $modGroup->removeMod($mod);
        }

        foreach ($this->modListRepository->findAll() as $modList) {
            $modList->removeMod($mod);
        }

        $this->entityManager->remove($mod);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list');
    }
}
