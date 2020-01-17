<?php

declare(strict_types=1);

namespace App\Service\Mission;

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
     * {@inheritdoc}
     */
    public function lookup(Request $request): ?Response
    {
        return $this->store->lookup($request);
    }

    /**
     * {@inheritdoc}
     */
    public function write(Request $request, Response $response): string
    {
        return $this->store->write($request, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function invalidate(Request $request): void
    {
        $this->store->invalidate($request);
    }

    /**
     * {@inheritdoc}
     */
    public function lock(Request $request)
    {
        return $this->store->lock($request);
    }

    /**
     * {@inheritdoc}
     */
    public function unlock(Request $request): bool
    {
        return $this->store->unlock($request);
    }

    /**
     * {@inheritdoc}
     */
    public function isLocked(Request $request)
    {
        return $this->store->isLocked($request);
    }

    /**
     * {@inheritdoc}
     */
    public function purge($url): bool
    {
        return $this->store->purge($url);
    }

    /**
     * {@inheritdoc}
     */
    public function cleanup(): void
    {
        $this->store->cleanup();
    }
}
