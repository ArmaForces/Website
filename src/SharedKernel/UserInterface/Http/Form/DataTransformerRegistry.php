<?php

declare(strict_types=1);

namespace App\SharedKernel\UserInterface\Http\Form;

use App\SharedKernel\Domain\Model\EntityInterface;

class DataTransformerRegistry implements DataTransformerInterface
{
    /**
     * @param RegisteredDataTransformerInterface[] $registeredDataTransformers
     */
    public function __construct(
        private iterable $registeredDataTransformers
    ) {
    }

    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        foreach ($this->registeredDataTransformers as $registeredDataTransformer) {
            if ($registeredDataTransformer->supportsTransformationToEntity($formDto, $entity)) {
                return $registeredDataTransformer->transformToEntity($formDto, $entity);
            }
        }

        throw $this->createException($formDto, $entity);
    }

    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        foreach ($this->registeredDataTransformers as $registeredDataTransformer) {
            if ($registeredDataTransformer->supportsTransformationFromEntity($formDto, $entity)) {
                return $registeredDataTransformer->transformFromEntity($formDto, $entity);
            }
        }

        throw $this->createException($formDto, $entity);
    }

    private function createException(FormDtoInterface $formDto, EntityInterface $entity = null): \Throwable
    {
        return new \InvalidArgumentException(
            sprintf(
                'None of the registered data transformers supports transformation of "%s" to "%s"',
                \get_class($formDto),
                $entity ? \get_class($formDto) : 'null',
            )
        );
    }
}
