<?php

declare(strict_types=1);

namespace App\Controller\ModGroup;

use App\Entity\ModGroup\ModGroup;
use App\Security\Enum\PermissionsEnum;
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

    #[Route('/mod-group/{name}/delete', name: 'app_mod_group_delete')]
    #[IsGranted(PermissionsEnum::MOD_GROUP_DELETE->value, 'modGroup')]
    public function __invoke(ModGroup $modGroup): Response
    {
        $this->entityManager->remove($modGroup);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_mod_group_list');
    }
}
