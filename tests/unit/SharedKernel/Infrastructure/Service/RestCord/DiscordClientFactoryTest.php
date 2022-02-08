<?php

declare(strict_types=1);

namespace App\Tests\Unit\SharedKernel\Infrastructure\Service\RestCord;

use App\SharedKernel\Infrastructure\Service\RestCord\DiscordClientFactory;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\SharedKernel\Infrastructure\Service\RestCord\DiscordClientFactory
 */
final class DiscordClientFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function createBotClient_validTokenType_returnsValidClient(): void
    {
        $token = 'some_token';
        $discordClientFactory = new DiscordClientFactory();
        $discordClient = $discordClientFactory->createBotClient($token);

        $reflection = new \ReflectionClass($discordClient);
        $optionsProperty = $reflection->getProperty('options');
        $optionsProperty->setAccessible(true);
        $optionsPropertyValue = $optionsProperty->getValue($discordClient);

        static::assertSame($token, $optionsPropertyValue['token']);
        static::assertSame('Bot', $optionsPropertyValue['tokenType']);
    }

    /**
     * @test
     */
    public function createUserClient_validTokenType_returnsValidClient(): void
    {
        $token = 'some_token';
        $discordClientFactory = new DiscordClientFactory();
        $discordClient = $discordClientFactory->createUserClient($token);

        $reflection = new \ReflectionClass($discordClient);
        $optionsProperty = $reflection->getProperty('options');
        $optionsProperty->setAccessible(true);
        $optionsPropertyValue = $optionsProperty->getValue($discordClient);

        static::assertSame($token, $optionsPropertyValue['token']);
        static::assertSame('OAuth', $optionsPropertyValue['tokenType']);
    }
}
