<?php

declare(strict_types=1);

namespace App\Form\ModList\Dto;

use App\Entity\User\UserInterface;
use App\Form\AbstractFormDto;
use App\Validator\ModList\UniqueModListNameHtml;

/**
 * @UniqueModListNameHtml(errorPath="attachment")
 */
class ModListFormDtoHtml extends AbstractFormDto
{
    protected ?string $attachment = null;

    protected ?UserInterface $owner = null;

    protected bool $active = true;

    protected bool $approved = false;

    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(?string $attachment): void
    {
        $this->attachment = $attachment;
    }

    public function getOwner(): ?UserInterface
    {
        return $this->owner;
    }

    public function setOwner(?UserInterface $owner): void
    {
        $this->owner = $owner;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isApproved(): bool
    {
        return $this->approved;
    }

    public function setApproved(bool $approved): void
    {
        $this->approved = $approved;
    }
}
