<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\AbstractDescribedEntity;
use App\Entity\Mod\Enum\ModTypeEnum;

abstract class AbstractMod extends AbstractDescribedEntity implements ModInterface
{
    /** @var ModTypeEnum */
    protected $type;

    public function __construct(string $name, ModTypeEnum $type)
    {
        parent::__construct($name);

        $this->type = $type;
    }

    public function getType(): ModTypeEnum
    {
        return $this->type;
    }

    public function setType(ModTypeEnum $type): void
    {
        $this->type = $type;
    }
}
