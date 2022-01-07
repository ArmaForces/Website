<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Test\Traits;

use App\DataFixtures\Mod\Required\ArmaForcesModsModFixture;
use App\DataFixtures\ModGroup\RhsModGroupFixture;
use App\DataFixtures\ModList\DefaultModListFixture;
use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;

trait DataProvidersTrait
{
    public function allUserTypesDataProvider(): array
    {
        return [
            'Anonymous user' => [''],
            'Regular user' => [RegularUserFixture::ID],
            'Admin user' => [AdminUserFixture::ID],
        ];
    }

    public function registeredUsersDataProvider(): array
    {
        return [
            'Regular user' => [RegularUserFixture::ID],
            'Admin user' => [AdminUserFixture::ID],
        ];
    }

    public function modsDataProvider(): array
    {
        return [
            'Steam Workshop, required' => [ArmaForcesModsModFixture::ID],
        ];
    }

    public function modGroupsDataProvider(): array
    {
        return [
            [RhsModGroupFixture::ID],
        ];
    }

    public function modListsDataProvider(): array
    {
        return [
            [DefaultModListFixture::ID],
        ];
    }
}
