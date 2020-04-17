<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Mod\SteamWorkshopMod;
use App\Form\Mod\Dto\ModFormDto;
use App\Service\SteamWorkshop\Helper\Exception\InvalidItemUrlFormatException;
use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSteamWorkshopModValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($modFormDto, Constraint $constraint): void
    {
        if (!$modFormDto instanceof ModFormDto) {
            throw new UnexpectedTypeException($constraint, ModFormDto::class);
        }

        if (!$constraint instanceof UniqueSteamWorkshopMod) {
            throw new UnexpectedTypeException($constraint, UniqueSteamWorkshopMod::class);
        }

        $url = $modFormDto->getUrl();
        if (!$url) {
            return;
        }

        try {
            $itemId = SteamWorkshopHelper::itemUrlToItemId($url);
        } catch (InvalidItemUrlFormatException $ex) {
            return;
        }

        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();
        $qb
            ->addSelect($expr->count('swm'))
            ->from(SteamWorkshopMod::class, 'swm')
            ->andWhere($expr->eq('swm.itemId', ':itemId'))
            ->setParameter('itemId', $itemId)
        ;

        $entityId = $modFormDto->getId();
        if ($entityId) {
            $qb
                ->andWhere($expr->neq('swm.id', ':id'))
                ->setParameter('id', $entityId)
            ;
        }

        $result = (int) $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        if (0 === $result) {
            return;
        }

        $errorPath = $constraint->errorPath;
        $violation = $this->context->buildViolation($constraint->message, [
            '{{ modUrl }}' => $url,
        ]);

        if ($errorPath) {
            $violation->atPath($errorPath);
        }

        $violation->addViolation();
    }
}
