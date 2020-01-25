<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\AbstractEntity;
use App\Enum\Mod\ModSourceEnum;
use App\Enum\Mod\ModTypeEnum;
use App\Enum\Mod\ModUsedByEnum;

class Mod extends AbstractEntity implements ModInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $path;

    /** @var string */
    protected $usedBy = ModUsedByEnum::CLIENT;

    /** @var string */
    protected $type = ModTypeEnum::REQUIRED;

    /** @var string */
    protected $source = ModSourceEnum::STEAM_WORKSHOP;

    public function __construct(string $name, string $path)
    {
        parent::__construct();

        $this->name = $name;
        $this->path = $path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getUsedBy(): string
    {
        return $this->usedBy;
    }

    public function setUsedBy(string $usedBy): void
    {
        $this->usedBy = $usedBy;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): void
    {
        $this->source = $source;
    }
}
