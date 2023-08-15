<?php

declare(strict_types=1);

namespace App\Controller\ModList;

use App\Entity\ModList\ModList;
use App\Security\Enum\PermissionsEnum;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteAction extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/mod-list/{name}/delete', name: 'app_mod_list_delete')]
    #[IsGranted(PermissionsEnum::MOD_LIST_DELETE, 'modList')]
    public function __invoke(ModList $modList): Response
    {
        $this->entityManager->remove($modList);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list_list');
    }
}
