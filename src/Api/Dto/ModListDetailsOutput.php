<?php

declare(strict_types=1);

namespace App\Api\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class ModListDetailsOutput extends ModListOutput
{
    /**
     * @Groups({"read"})
     *
     * @var ModOutput[]
     */
    public $mods;
}
