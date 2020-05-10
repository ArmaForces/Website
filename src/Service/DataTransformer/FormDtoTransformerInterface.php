<?php

declare(strict_types=1);

namespace App\Service\DataTransformer;

use App\Entity\EntityInterface;
use App\Form\FormDtoInterface;

interface FormDtoTransformerInterface
{
    /**
     * Convert Dto to new entity instance or fill existing instance entity with Dto data.
     */
    public function toEntity(FormDtoInterface $from, EntityInterface $to = null): EntityInterface;

    /**
     * Crete new Dto instance from Entity.
     */
    public function fromEntity(EntityInterface $from = null): FormDtoInterface;
}
