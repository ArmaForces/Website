<?php

declare(strict_types=1);

namespace App\Tests\Unit\Pmc\Service\Dto;

use App\Pmc\Service\Dynulo\Dto\ItemDto;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\Pmc\Service\Dynulo\Dto\ItemDto
 */
final class ItemDtoTest extends TestCase
{
    /**
     * @test
     */
    public function jsonSerialize(): void
    {
        $dtoWithTraits = new ItemDto('cup_arifle_m4a1_black', 'M4A1', 1500, [], null);
        static::assertSame([
            'class' => 'cup_arifle_m4a1_black',
            'pretty' => 'M4A1',
            'cost' => 1500,
            'traits' => '',
        ], $dtoWithTraits->jsonSerialize());

        $dtoWithoutTraits = new ItemDto('', '', 0, ['trait1', 'trait2'], null);
        static::assertSame([
            'class' => '',
            'pretty' => '',
            'cost' => 0,
            'traits' => 'trait1|trait2',
        ], $dtoWithoutTraits->jsonSerialize());
    }
}
