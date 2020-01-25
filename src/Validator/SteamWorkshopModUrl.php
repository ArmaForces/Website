<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class SteamWorkshopModUrl extends Regex
{
    /** @var string */
    public $message = 'Incorrect format of Steam Workshop mod URL';

    /** @var string */
    public $pattern = '/https:\/\/steamcommunity\.com\/sharedfiles\/filedetails\/\?id=\d{10}/';

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return [];
    }
}
