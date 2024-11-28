<?php

declare(strict_types=1);

namespace App\Tests\Functional\Attendances\Api\Attendance;

use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class ListAttendancesCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function listAttendancesWithoutApiKey(FunctionalTester $I): void
    {
        $I->sendGet('/api/attendances', [
            'order[missionId]' => 'ASC',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    public function listAttendancesUsingInvalidApiKey(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('invalid_key');

        $I->sendGet('/api/attendances', [
            'order[missionId]' => 'ASC',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    public function listAttendancesUsingValidApiKey(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('test_key');

        $I->sendGet('/api/attendances', [
            'order[missionId]' => 'ASC',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    private function getExpectedPayload(): array
    {
        return [
            'data' => [
                [
                    'id' => '2694264f-0e6f-40a6-9596-bc22d1eaa2c7',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'missionId' => 'mission_1',
                    'playerId' => 76561198048200529,
                ],
                [
                    'id' => 'd2a60cd4-d3f0-497a-9840-69fa5c388f1e',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'missionId' => 'mission_2',
                    'playerId' => 76561198048200529,
                ],
                [
                    'id' => '0a918bf1-d20f-4856-aa0d-f093f6e90bf7',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'missionId' => 'mission_3',
                    'playerId' => 76561198048200529,
                ],
                [
                    'id' => '2a3aa170-3a79-471f-b6ec-e194bf8d6c9e',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'missionId' => 'mission_4',
                    'playerId' => 76561198048200529,
                ],
                [
                    'id' => 'ac22546b-9d5c-4a54-a713-01dcc5ee40e6',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'missionId' => 'mission_5',
                    'playerId' => 76561198048200529,
                ],
            ],
            'items' => 5,
            'totalItems' => 5.0,
            'currentPage' => 1.0,
            'lastPage' => 1.0,
            'itemsPerPage' => 30.0,
        ];
    }
}
