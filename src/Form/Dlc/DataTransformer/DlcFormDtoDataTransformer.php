<?php

declare(strict_types=1);

namespace App\Form\Dlc\DataTransformer;

use App\Entity\Dlc\Dlc;
use App\Form\Dlc\Dto\DlcFormDto;
use App\Service\SteamApiClient\Helper\SteamHelper;
use App\Service\SteamApiClient\SteamApiClientInterface;
use Ramsey\Uuid\Uuid;

class DlcFormDtoDataTransformer
{
    public function __construct(
        private SteamApiClientInterface $steamApiClient
    ) {
    }

    public function transformToEntity(DlcFormDto $dlcFormDto, Dlc $dlc = null): Dlc
    {
        $appId = SteamHelper::appUrlToAppId($dlcFormDto->getUrl());
        $directory = $dlcFormDto->getDirectory();
        $name = $dlcFormDto->getName() ?? substr($this->steamApiClient->getAppInfo($appId)->getName(), 0, 255);

        if (!$dlc instanceof Dlc) {
            return new Dlc(
                Uuid::uuid4(),
                $name,
                $dlcFormDto->getDescription(),
                $appId,
                $directory
            );
        }

        $dlc->update(
            $name,
            $dlcFormDto->getDescription(),
            $appId,
            $directory
        );

        return $dlc;
    }

    public function transformFromEntity(DlcFormDto $dlcFormDto, Dlc $dlc = null): DlcFormDto
    {
        if (!$dlc instanceof Dlc) {
            return $dlcFormDto;
        }

        $dlcFormDto->setId($dlc->getId());
        $dlcFormDto->setName($dlc->getName());
        $dlcFormDto->setDescription($dlc->getDescription());
        $url = SteamHelper::appIdToAppUrl($dlc->getAppId());
        $dlcFormDto->setUrl($url);
        $dlcFormDto->setDirectory($dlc->getDirectory());

        return $dlcFormDto;
    }
}
