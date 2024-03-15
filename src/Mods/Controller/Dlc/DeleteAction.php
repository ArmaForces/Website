<?php

declare(strict_types=1);

namespace App\Mods\Controller\Dlc;

use App\Mods\Entity\Dlc\Dlc;
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

    #[Route('/dlc/{id}/delete', name: 'app_dlc_delete')]
    #[IsGranted(PermissionsEnum::DLC_DELETE->value, 'dlc')]
    public function __invoke(Dlc $dlc): Response
    {
        $this->entityManager->remove($dlc);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_dlc_list');
    }
}
