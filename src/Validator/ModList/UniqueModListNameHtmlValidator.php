<?php

declare(strict_types=1);

namespace App\Validator\ModList;

use App\Entity\ModList\ModList;
use App\Form\ModList\Dto\ModListFormDto;
use App\Form\ModList\Dto\ModListFormDtoHtml;
use App\Service\LegacyModListImport\ModListImportHtml;
use App\Validator\AbstractValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueModListNameHtmlValidator extends AbstractValidator
{
    public function __construct(EntityManagerInterface $entityManager, private ModListImportHtml $modListImportHtml)
    {
        $this->entityManager = $entityManager;
        parent::__construct($entityManager);
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof ModListFormDtoHtml) {
            throw new UnexpectedTypeException($constraint, ModListFormDto::class);
        }

        if (!$constraint instanceof UniqueModListNameHtml) {
            throw new UnexpectedTypeException($constraint, UniqueModListName::class);
        }

        $modlistName = $this->modListImportHtml->importFromDirectoryHtml($value->getAttachment())->getModlistName();
        if ($this->isColumnValueUnique(ModList::class, ['name' => $modlistName])) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ modListName }}' => $modlistName,
            ],
            $constraint->errorPath
        );
    }
}
