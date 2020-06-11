<?php

declare(strict_types=1);

namespace App\Validator\ModList;

use App\Entity\ModList\ModList;
use App\Form\ModList\Dto\ModListFormDto;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueModListNameValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($modListFormDto, Constraint $constraint): void
    {
        if (!$modListFormDto instanceof ModListFormDto) {
            throw new UnexpectedTypeException($constraint, ModListFormDto::class);
        }

        if (!$constraint instanceof UniqueModListName) {
            throw new UnexpectedTypeException($constraint, UniqueModListName::class);
        }

        $name = $modListFormDto->getName();
        if (!$name) {
            return;
        }

        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();
        $qb
            ->addSelect($expr->count('ml'))
            ->from(ModList::class, 'ml')
            ->andWhere($expr->eq('ml.name', ':name'))
            ->setParameter('name', $name)
        ;

        $entityId = $modListFormDto->getId();
        if ($entityId) {
            $qb
                ->andWhere($expr->neq('ml.id', ':id'))
                ->setParameter('id', $entityId)
            ;
        }

        $result = (int) $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        if (0 === $result) {
            return;
        }

        $errorPath = $constraint->errorPath;
        $violation = $this->context->buildViolation($constraint->message, [
            '{{ modListName }}' => $name,
        ]);

        if ($errorPath) {
            $violation->atPath($errorPath);
        }

        $violation->addViolation();
    }
}
