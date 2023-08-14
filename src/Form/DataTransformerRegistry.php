<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\AbstractEntity;

class DataTransformerRegistry implements DataTransformerInterface
{
    /**
     * @param RegisteredDataTransformerInterface[] $registeredDataTransformers
     */
    public function __construct(
        private iterable $registeredDataTransformers
    ) {
    }

    public function transformToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): AbstractEntity
    {
        foreach ($this->registeredDataTransformers as $registeredDataTransformer) {
            if ($registeredDataTransformer->supportsTransformationToEntity($formDto, $entity)) {
                return $registeredDataTransformer->transformToEntity($formDto, $entity);
            }
        }

        throw $this->createException($formDto, $entity);
    }

    public function transformFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): FormDtoInterface
    {
        foreach ($this->registeredDataTransformers as $registeredDataTransformer) {
            if ($registeredDataTransformer->supportsTransformationFromEntity($formDto, $entity)) {
                return $registeredDataTransformer->transformFromEntity($formDto, $entity);
            }
        }

        throw $this->createException($formDto, $entity);
    }

    private function createException(FormDtoInterface $formDto, AbstractEntity $entity = null): \Throwable
    {
        return new \InvalidArgumentException(
            sprintf(
                'None of the registered data transformers supports transformation of "%s" to "%s"',
                $formDto::class,
                $entity ? $formDto::class : 'null',
            )
        );
    }
}
