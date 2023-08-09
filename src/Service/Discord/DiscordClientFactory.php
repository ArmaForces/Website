<?php

declare(strict_types=1);

namespace App\Service\Discord;

use Discord\Http\Drivers\React;
use Discord\Http\Http;
use Psr\Log\LoggerInterface;
use React\EventLoop\Loop;

class DiscordClientFactory
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function createBotClient(string $botToken): Http
    {
        $token = sprintf('Bot %s', $botToken);
        $loop = Loop::get();

        return new Http(
            $token,
            $loop,
            $this->logger,
            new React($loop)
        );
    }

    public function createUserClient(string $userToken): Http
    {
        $token = sprintf('Bearer %s', $userToken);
        $loop = Loop::get();

        return new Http(
            $token,
            $loop,
            $this->logger,
            new React($loop)
        );
    }
}
