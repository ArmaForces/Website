<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use App\Users\Entity\User\User;

trait SecurityAssertsTrait
{
    public function amDiscordAuthenticatedAs(string $id, callable $preAuthCallback = null): User
    {
        /** @var User $user */
        $user = $this->grabEntityFromRepository(User::class, ['id' => $id]);
        if ($preAuthCallback) {
            $preAuthCallback($user);

            // Refresh user entity to avoid permission issues in subsequent requests
            $this->haveInRepository($user);
        }
        $this->amLoggedInAs($user);

        return $user;
    }

    public function amApiKeyAuthenticatedAs(string $apiKey, string $headerName = 'X-API-KEY'): void
    {
        $this->haveHttpHeader($headerName, $apiKey);
    }
}
