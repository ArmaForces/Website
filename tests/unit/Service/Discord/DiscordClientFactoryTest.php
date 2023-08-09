<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Discord;

use App\Service\Discord\DiscordClientFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * @covers \App\Service\Discord\DiscordClientFactory
 */
final class DiscordClientFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function createBotClient_validTokenType_returnsValidClient(): void
    {
        $token = 'some_token';
        $logger = $this->createMock(LoggerInterface::class);
        $discordClientFactory = new DiscordClientFactory($logger);
        $discordClient = $discordClientFactory->createBotClient($token);

        $reflection = new \ReflectionClass($discordClient);
        $tokenProperty = $reflection->getProperty('token');
        $tokenProperty->setAccessible(true);
        $tokenPropertyValue = $tokenProperty->getValue($discordClient);

        static::assertSame(sprintf('Bot %s', $token), $tokenPropertyValue);
    }

    /**
     * @test
     */
    public function createUserClient_validTokenType_returnsValidClient(): void
    {
        $token = 'some_token';
        $logger = $this->createMock(LoggerInterface::class);
        $discordClientFactory = new DiscordClientFactory($logger);
        $discordClient = $discordClientFactory->createUserClient($token);

        $reflection = new \ReflectionClass($discordClient);
        $tokenProperty = $reflection->getProperty('token');
        $tokenProperty->setAccessible(true);
        $tokenPropertyValue = $tokenProperty->getValue($discordClient);

        static::assertSame(sprintf('Bearer %s', $token), $tokenPropertyValue);
    }
}
