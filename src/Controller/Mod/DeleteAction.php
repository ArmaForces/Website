<?php

declare(strict_types=1);

namespace App\Controller\Mod;

use App\Entity\Mod\AbstractMod;
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

    #[Route('/mod/{id}/delete', name: 'app_mod_delete')]
    #[IsGranted(PermissionsEnum::MOD_DELETE, 'mod')]
    public function __invoke(AbstractMod $mod): Response
    {
        $this->entityManager->remove($mod);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_list');
    }
}
