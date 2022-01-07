<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Validator;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\ConstraintValidator;

abstract class AbstractValidator extends ConstraintValidator
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function isColumnValueUnique(
        string $className,
        array $columnToValueMappings,
        ?UuidInterface $id = null,
        ?string $idColumn = 'id'
    ): bool {
        $connection = $this->entityManager->getConnection();
        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();
        $qb
            ->addSelect($expr->count('e'))
            ->from($className, 'e')
        ;

        $andExpr = $expr->andX();
        foreach ($columnToValueMappings as $column => $value) {
            $andExpr->add($expr->eq("e.{$column}", $connection->quote($value)));
        }

        $qb->andWhere($andExpr);

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
