<?php

declare(strict_types=1);

namespace App\Controller\Dlc;

use App\Entity\Dlc\Dlc;
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

    #[Route('/dlc/{id}/delete', name: 'app_dlc_delete')]
    #[IsGranted(PermissionsEnum::DLC_DELETE, 'dlc')]
    public function __invoke(Dlc $dlc): Response
    {
        $this->entityManager->remove($dlc);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_dlc_list');
    }
}
