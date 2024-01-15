<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\ModList;

use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class ListModListsCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function listModListsWithoutApiKey(FunctionalTester $I): void
    {
        $I->sendGet('/api/mod-lists', [
            'order[name]' => 'ASC',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    public function listModListsUsingInvalidApiKey(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('invalid_key');

        $I->sendGet('/api/mod-lists', [
            'order[name]' => 'ASC',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    public function listModListsUsingValidApiKey(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('test_key');

        $I->sendGet('/api/mod-lists', [
            'order[name]' => 'ASC',
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    private function getExpectedPayload(): array
    {
        return [
            'data' => [
                [
                    'id' => 'ea384489-c06c-4844-9e56-0e9a9c46bfaf',
                    'name' => 'CUP',
                    'active' => true,
                    'approved' => false,
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                ],
                [
                    'id' => 'f3e04dae-18a8-4533-99ea-d6d763ebabcf',
                    'name' => 'Default',
                    'active' => true,
                    'approved' => true,
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                ],
                [
                    'id' => 'c3b11c2f-9254-4262-bfde-3605df0149d4',
                    'name' => 'RHS',
                    'active' => false,
                    'approved' => false,
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                ],
            ],
            'items' => 3,
            'totalItems' => 3.0,
            'currentPage' => 1.0,
            'lastPage' => 1.0,
            'itemsPerPage' => 30.0,
        ];
    }
}
