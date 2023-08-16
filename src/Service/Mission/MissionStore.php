<?php

declare(strict_types=1);

namespace App\Service\Mission;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;

class MissionStore implements StoreInterface
{
    private Store $store;

    public function __construct(string $storagePath)
    {
        $this->store = new Store($storagePath);
    }

    public function lookup(Request $request): ?Response
    {
        return $this->store->lookup($request);
    }

    public function write(Request $request, Response $response): string
    {
        return $this->store->write($request, $response);
    }

    public function invalidate(Request $request): void
    {
        $this->store->invalidate($request);
    }

    public function lock(Request $request): bool|string
    {
        return $this->store->lock($request);
    }

    public function unlock(Request $request): bool
    {
        return $this->store->unlock($request);
    }

    public function isLocked(Request $request): bool
    {
        return $this->store->isLocked($request);
    }

    public function purge(string $url): bool
    {
        return $this->store->purge($url);
    }

    public function cleanup(): void
    {
        $this->store->cleanup();
    }
}
