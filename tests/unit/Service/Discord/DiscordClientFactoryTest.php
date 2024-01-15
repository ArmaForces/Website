<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Discord;

use App\Service\Discord\DiscordClientFactory;
use Codeception\Test\Unit;
use Psr\Log\LoggerInterface;

final class DiscordClientFactoryTest extends Unit
{
    public function testCreatesBotClient(): void
    {
        $token = 'some_token';
        $logger = $this->createMock(LoggerInterface::class);
        $discordClientFactory = new DiscordClientFactory($logger);
        $discordClient = $discordClientFactory->createBotClient($token);

        $reflection = new \ReflectionClass($discordClient);
        $tokenProperty = $reflection->getProperty('token');
        $tokenProperty->setAccessible(true);
        $tokenPropertyValue = $tokenProperty->getValue($discordClient);

        self::assertSame(sprintf('Bot %s', $token), $tokenPropertyValue);
    }

    public function testCreatesUserClient(): void
    {
        $token = 'some_token';
        $logger = $this->createMock(LoggerInterface::class);
        $discordClientFactory = new DiscordClientFactory($logger);
        $discordClient = $discordClientFactory->createUserClient($token);

        $reflection = new \ReflectionClass($discordClient);
        $tokenProperty = $reflection->getProperty('token');
        $tokenProperty->setAccessible(true);
        $tokenPropertyValue = $tokenProperty->getValue($discordClient);

        self::assertSame(sprintf('Bearer %s', $token), $tokenPropertyValue);
    }
}
