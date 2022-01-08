<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Test\Traits;

use App\ModManagement\Infrastructure\DataFixtures\Mod\Required\ArmaForcesModsModFixture;
use App\ModManagement\Infrastructure\DataFixtures\ModGroup\RhsModGroupFixture;
use App\ModManagement\Infrastructure\DataFixtures\ModList\DefaultModListFixture;
use App\UserManagement\Infrastructure\DataFixtures\User\AdminUserFixture;
use App\UserManagement\Infrastructure\DataFixtures\User\RegularUserFixture;

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
