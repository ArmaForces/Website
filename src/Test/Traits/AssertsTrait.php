<?php

declare(strict_types=1);

namespace App\Test\Traits;

use Symfony\Component\HttpFoundation\Response;

trait AssertsTrait
{
    public static function assertResponseRedirectsTo(string $location): void
    {
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
        self::assertResponseHeaderSame('Location', $location);
    }

    public static function assertResponseRedirectsToLoginPage(): void
    {
        self::assertResponseRedirectsTo('/security/connect/discord');
    }

    public static function assertResponseRedirectsToUserList(): void
    {
        self::assertResponseRedirectsTo('/user/list');
    }

    public static function assertResponseRedirectsToModList(): void
    {
        self::assertResponseRedirectsTo('/mod/list');
    }

    public static function assertResponseRedirectsToModGroupList(): void
    {
        self::assertResponseRedirectsTo('/mod-group/list');
    }

    public static function assertResponseRedirectsToModListList(): void
    {
        self::assertResponseRedirectsTo('/mod-list/list');
    }
}
