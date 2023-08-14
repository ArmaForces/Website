<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\AbstractEntity;

interface DataTransformerInterface
{
    /**
     * Transform form DTO to existing entity or create new entity from given form DTO.
     */
    public function transformToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): AbstractEntity;

    /**
     * Transform existing form DTO to given entity or create new form DTO.
     */
    public function transformFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): FormDtoInterface;
}
