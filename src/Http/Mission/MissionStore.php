<?php

declare(strict_types=1);

namespace App\Http\Mission;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;

class MissionStore implements StoreInterface
{
    /** @var Store */
    protected $store;

    public function __construct(string $storagePath)
    {
        $this->store = new Store($storagePath);
    }

    /**
     * @inheritDoc
     */
    public function lookup(Request $request): ?Response
    {
        return $this->store->lookup($request);
    }

    /**
     * @inheritDoc
     */
    public function write(Request $request, Response $response): string
    {
        return $this->store->write($request, $response);
    }

    /**
     * @inheritDoc
     */
    public function invalidate(Request $request): void
    {
        $this->store->invalidate($request);
    }

    /**
     * @inheritDoc
     */
    public function lock(Request $request)
    {
        return $this->store->lock($request);
    }

    /**
     * @inheritDoc
     */
    public function unlock(Request $request): bool
    {
        return $this->store->unlock($request);
    }

    /**
     * @inheritDoc
     */
    public function isLocked(Request $request)
    {
        return $this->store->isLocked($request);
    }

    /**
     * @inheritDoc
     */
    public function purge($url): bool
    {
        return $this->store->purge($url);
    }

    /**
     * @inheritDoc
     */
    public function cleanup(): void
    {
        $this->store->cleanup();
    }
}
