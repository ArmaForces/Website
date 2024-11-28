<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList\External;

use App\Mods\Entity\ModList\ExternalModList;
use App\Shared\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DeleteAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/external-mod-list/{name}/delete', name: 'app_external_mod_list_delete')]
    #[IsGranted(PermissionsEnum::EXTERNAL_MOD_LIST_DELETE->value, 'externalModList')]
    public function __invoke(ExternalModList $externalModList): Response
    {
        $this->entityManager->remove($externalModList);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list_list');
    }
}
