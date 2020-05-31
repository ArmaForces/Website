<?php

declare(strict_types=1);

namespace App\Api\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class ModListOutput
{
    /**
     * @Groups({"read"})
     *
     * @var string
     */
    public $id;

    /**
     * @Groups({"read"})
     *
     * @var string
     */
    public $name;

    /**
     * @Groups({"read"})
     *
     * @var \DateTimeInterface
     */
    public $createdAt;

    /**
     * @Groups({"read"})
     *
     * @var \DateTimeInterface
     */
    public $lastUpdatedAt;

    /**
     * @Groups({"read"})
     *
     * @var ModOutput
     */
    public $mods;
}
