<?php

declare(strict_types=1);

namespace App\Validator\UserGroup;

use App\Entity\UserGroup\UserGroup;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserGroupNameValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($userGroupFormDto, Constraint $constraint): void
    {
        if (!$userGroupFormDto instanceof UserGroupFormDto) {
            throw new UnexpectedTypeException($constraint, UserGroupFormDto::class);
        }

        if (!$constraint instanceof UniqueUserGroupName) {
            throw new UnexpectedTypeException($constraint, UniqueUserGroupName::class);
        }

        $name = $userGroupFormDto->getName();
        if (!$name) {
            return;
        }

        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();
        $qb
            ->addSelect($expr->count('ug'))
            ->from(UserGroup::class, 'ug')
            ->andWhere($expr->eq('ug.name', ':name'))
            ->setParameter('name', $name)
        ;

        $entityId = $userGroupFormDto->getId();
        if ($entityId) {
            $qb
                ->andWhere($expr->neq('ug.id', ':id'))
                ->setParameter('id', $entityId)
            ;
        }

        $result = (int) $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        if (0 === $result) {
            return;
        }

        $errorPath = $constraint->errorPath;
        $violation = $this->context->buildViolation($constraint->message, [
            '{{ userGroupName }}' => $name,
        ]);

        if ($errorPath) {
            $violation->atPath($errorPath);
        }

        $violation->addViolation();
    }
}
