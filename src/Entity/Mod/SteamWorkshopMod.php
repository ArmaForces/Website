<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModTypeEnum;

class SteamWorkshopMod extends AbstractMod
{
    /** @var string */
    protected $url;

    public function __construct(string $name, ModTypeEnum $type, string $url)
    {
        parent::__construct($name, $type);

        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }
}
