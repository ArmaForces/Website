<?php

declare(strict_types=1);

namespace App\Mods\Controller\ModList\Standard;

use App\Mods\Entity\ModList\StandardModList;
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

    #[Route('/standard-mod-list/{name}/delete', name: 'app_standard_mod_list_delete')]
    #[IsGranted(PermissionsEnum::STANDARD_MOD_LIST_DELETE->value, 'standardModList')]
    public function __invoke(StandardModList $standardModList): Response
    {
        $this->entityManager->remove($standardModList);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list_list');
    }
}
