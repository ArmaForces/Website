<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ModList\ModList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mod-list", name="app_mod_list")
 */
class ModListDownloadController extends AbstractController
{
    /**
     * @Route("/{id}/download", name="_download")
     */
    public function downloadAction(ModList $modList): Response
    {
        $template = $this->renderView('mod_list_download/template.html.twig', [
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
