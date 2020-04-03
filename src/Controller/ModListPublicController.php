<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ModList\ModList;
use App\Repository\ModListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mod-list", name="app_mod_list_public")
 */
class ModListPublicController extends AbstractController
{
    /** @var ModListRepository */
    protected $modListRepository;

    public function __construct(ModListRepository $modListRepository)
    {
        $this->modListRepository = $modListRepository;
    }

    /**
     * @Route("/select", name="_select")
     */
    public function selectAction(): Response
    {
        $modLists = $this->modListRepository->findBy([], ['name' => 'ASC']);

        return $this->render('mod_list_public/select.html.twig', [
            'modLists' => $modLists,
        ]);
    }

    /**
     * @Route("/{id}/customize", name="_customize")
     */
    public function customizeAction(ModList $modList): Response
    {
        return $this->render('mod_list_public/customize.html.twig', [
            'modList' => $modList,
        ]);
    }

    /**
     * @Route("/{id}/download", name="_download")
     */
    public function downloadAction(ModList $modList): Response
    {
        $template = $this->renderView('mod_list_public/launcher_preset_template.html.twig', [
            'modList' => $modList,
        ]);

        $response = new Response($template);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $modList->getName().'.html'
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
