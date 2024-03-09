<?php

declare(strict_types=1);

namespace App\Shared\Doctrine\Dbal\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType as BaseBigIntType;

class BigIntType extends BaseBigIntType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): ?int
    {
        return null === $value ? null : (int) $value;
    }
}
