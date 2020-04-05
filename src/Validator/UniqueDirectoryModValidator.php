<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Mod\DirectoryMod;
use App\Form\Mod\Dto\ModFormDto;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDirectoryModValidator extends ConstraintValidator
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

        if (!$constraint instanceof UniqueDirectoryMod) {
            throw new UnexpectedTypeException($constraint, UniqueDirectoryMod::class);
        }

        $directory = $modFormDto->getDirectory();
        if (!$directory) {
            return;
        }

        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();
        $qb
            ->addSelect($expr->count('dm'))
            ->from(DirectoryMod::class, 'dm')
            ->andWhere($expr->eq('dm.directory', ':directory'))
            ->setParameter('directory', $directory)
        ;

        $entityId = $modFormDto->getId();
        if ($entityId) {
            $qb
                ->andWhere($expr->neq('dm.id', ':id'))
                ->setParameter('id', $entityId)
            ;
        }

        $result = (int) $qb->getQuery()->getResult(AbstractQuery::HYDRATE_SINGLE_SCALAR);
        if (0 === $result) {
            return;
        }

        $errorPath = $constraint->errorPath;
        $violation = $this->context->buildViolation($constraint->message, [
            '{{ directoryName }}' => $directory,
        ]);

        if ($errorPath) {
            $violation->atPath($errorPath);
        }

        $violation->addViolation();
    }
}
