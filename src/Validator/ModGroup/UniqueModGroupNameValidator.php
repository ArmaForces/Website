<?php

declare(strict_types=1);

namespace App\Validator\ModGroup;

use App\Entity\ModGroup\ModGroup;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueModGroupNameValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($modGroupFormDto, Constraint $constraint): void
    {
        if (!$modGroupFormDto instanceof ModGroupFormDto) {
            throw new UnexpectedTypeException($constraint, ModGroupFormDto::class);
        }

        if (!$constraint instanceof UniqueModGroupName) {
            throw new UnexpectedTypeException($constraint, UniqueModGroupName::class);
        }

        $name = $modGroupFormDto->getName();
        if (!$name) {
            return;
        }

        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();
        $qb
            ->addSelect($expr->count('ml'))
            ->from(ModGroup::class, 'ml')
            ->andWhere($expr->eq('ml.name', ':name'))
            ->setParameter('name', $name)
        ;

        $entityId = $modGroupFormDto->getId();
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
            '{{ modGroupName }}' => $name,
        ]);

        if ($errorPath) {
            $violation->atPath($errorPath);
        }

        $violation->addViolation();
    }
}
