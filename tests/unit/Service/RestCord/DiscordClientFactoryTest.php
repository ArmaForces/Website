<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\RestCord;

use App\Service\RestCord\DiscordClientFactory;
use App\Service\RestCord\Enum\TokenTypeEnum;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\Service\RestCord\DiscordClientFactory
 */
final class DiscordClientFactoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider tokenTypes
     */
    public function createDiscordClient_validTokenType_returnsValidClient(TokenTypeEnum $tokenType): void
    {
        $token = 'some_token';
        $discordClientFactory = new DiscordClientFactory();
        $discordClient = $discordClientFactory->createFromToken($token, $tokenType);

        $reflection = new \ReflectionClass($discordClient);
        $optionsProperty = $reflection->getProperty('options');
        $optionsProperty->setAccessible(true);
        $optionsPropertyValue = $optionsProperty->getValue($discordClient);

        static::assertSame($token, $optionsPropertyValue['token']);
        static::assertSame($tokenType->getValue(), $optionsPropertyValue['tokenType']);
    }

    public function tokenTypes(): array
    {
        return [
            TokenTypeEnum::TOKEN_TYPE_OAUTH => [TokenTypeEnum::get(TokenTypeEnum::TOKEN_TYPE_OAUTH)],
            TokenTypeEnum::TOKEN_TYPE_BOT => [TokenTypeEnum::get(TokenTypeEnum::TOKEN_TYPE_BOT)],
        ];
    }
}
