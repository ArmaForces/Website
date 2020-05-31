<?php

declare(strict_types=1);

namespace App\Api\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class ModOutput
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
    public $source;

    /**
     * @Groups({"read"})
     *
     * @var string
     */
    public $type;

    /**
     * @Groups({"read"})
     *
     * @var string
     */
    public $name;

    /**
     * @Groups({"read"})
     *
     * @var int
     */
    public $workshopId;

    /**
     * @Groups({"read"})
     *
     * @var string
     */
    public $directory;

    /**
     * @Groups({"read"})
     *
     * @var \DateTimeInterface
     */
    public $createdAt;

    /**
     * @Groups({"read"})
     *
     * @var null|\DateTimeInterface
     */
    public $lastUpdatedAt;
}
