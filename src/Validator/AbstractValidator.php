<?php

declare(strict_types=1);

namespace App\Validator;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\ConstraintValidator;

abstract class AbstractValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isColumnValueUnique(
        string $className,
        string $value,
        ?UuidInterface $id = null,
        ?string $valueColumn = 'name',
        ?string $idColumn = 'id'
    ): bool {
        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();
        $qb
            ->addSelect($expr->count('e'))
            ->from($className, 'e')
            ->andWhere($expr->eq("e.{$valueColumn}", ':value'))
            ->setParameter('value', $value)
        ;

        if ($id) {
            $qb
                ->andWhere($expr->neq("e.{$idColumn}", ':id'))
                ->setParameter('id', $id)
            ;
        }

        $result = (int) $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);

        return 0 === $result;
    }

    public function addViolation(string $message, array $parameters, ?string $errorPath): void
    {
        $violation = $this->context->buildViolation($message, $parameters);

        if ($errorPath) {
            $violation->atPath($errorPath);
        }

        $violation->addViolation();
    }
}
