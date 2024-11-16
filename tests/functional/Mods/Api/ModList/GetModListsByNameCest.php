<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Api\ModList;

use App\Mods\DataFixtures\ModList\Standard\DefaultStandardModListFixture;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class GetModListsByNameCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function getModListByNameWithoutApiKey(FunctionalTester $I): void
    {
        $I->sendGet(sprintf('/api/mod-lists/by-name/%s', DefaultStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    public function getModListByNameUsingInvalidApiKey(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('invalid_key');

        $I->sendGet(sprintf('/api/mod-lists/by-name/%s', DefaultStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    public function getModListByNameUsingValidApiKey(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('test_key');

        $I->sendGet(sprintf('/api/mod-lists/by-name/%s', DefaultStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson($this->getExpectedPayload());
    }

    public function getModListByNameUsingValidApiKeyWhenModListDoesNotExist(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('test_key');

        $I->sendGet(sprintf('/api/mod-lists/by-name/%s', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseContainsJson([
            'title' => 'An error occurred',
            'detail' => 'Not Found',
            'status' => HttpCode::NOT_FOUND,
            'type' => '/errors/404',
        ]);
    }

    private function getExpectedPayload(): array
    {
        return [
            'mods' => [
                [
                    'id' => '37f58e30-5194-4594-89af-4a82c7fc02be',
                    'name' => 'ACE Interaction Menu Expansion',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'optional',
                    'itemId' => 1376867375,
                    'directory' => null,
                ],
                [
                    'id' => '5506ae1b-2851-40e7-a15a-48f1fe6daaed',
                    'name' => 'Arma Script Profiler',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'directory',
                    'status' => null,
                    'type' => 'server_side',
                    'itemId' => null,
                    'directory' => '@Arma Script Profiler',
                ],
                [
                    'id' => '2f1d2dea-a7a6-4509-b478-66a980d724ca',
                    'name' => 'ArmaForces - ACE Medical [OBSOLETE]',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => 'broken',
                    'type' => 'required',
                    'itemId' => 1704054308,
                    'directory' => null,
                ],
                [
                    'id' => '0e4e059c-eef6-42a9-aec3-abdab344ec21',
                    'name' => 'ArmaForces - Medical',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 1981535406,
                    'directory' => null,
                ],
                [
                    'id' => '3e67f919-6880-4299-a2af-11b76ec594e6',
                    'name' => 'CUP Terrains - Core',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 583496184,
                    'directory' => null,
                ],
                [
                    'id' => '86b6e354-055a-4d8d-a877-ca4bb2f58a2e',
                    'name' => 'CUP Terrains - Maps',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 583544987,
                    'directory' => null,
                ],
                [
                    'id' => '7f55e10e-e005-4852-9a3f-1e28ae53414c',
                    'name' => 'CUP Units',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 497661914,
                    'directory' => null,
                ],
                [
                    'id' => '7275d787-d3c2-43b3-a2fa-cc881b1b052f',
                    'name' => 'CUP Vehicles',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 541888371,
                    'directory' => null,
                ],
                [
                    'id' => '6b177a8c-8289-46c6-9a6a-401d0c841edf',
                    'name' => 'CUP Weapons',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 497660133,
                    'directory' => null,
                ],
                [
                    'id' => '50b2c68a-1ea0-44b8-9b4d-6e0a47627d47',
                    'name' => 'R3',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'directory',
                    'status' => 'deprecated',
                    'type' => 'server_side',
                    'itemId' => null,
                    'directory' => '@R3',
                ],
                [
                    'id' => '91e0eaf1-3cf1-4b2c-98a9-a2d74a69fa76',
                    'name' => 'RHS AFRF',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 843425103,
                    'directory' => null,
                ],
                [
                    'id' => 'a34b691c-50a2-475e-bd0a-43e707c45db5',
                    'name' => 'RHS GREF',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 843593391,
                    'directory' => null,
                ],
                [
                    'id' => 'a37a6a74-3e82-4955-a836-0732d7816cc1',
                    'name' => 'RHS USAF',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 843577117,
                    'directory' => null,
                ],
                [
                    'id' => 'b8e88103-69d2-438b-8d89-933ccfdb3a5a',
                    'name' => '[OBSOLETE] ArmaForces - JBAD Building Fix',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => 'disabled',
                    'type' => 'required',
                    'itemId' => 1781106281,
                    'directory' => null,
                ],
                [
                    'id' => '7e11c37e-930e-49e8-a87d-8f942d98edb0',
                    'name' => '[legacy] ArmaForces - Mods',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => 'deprecated',
                    'type' => 'required',
                    'itemId' => 1639399387,
                    'directory' => null,
                ],
            ],
            'dlcs' => [
                [
                    'id' => 'ebd772ce-e5b5-4813-9ad0-777915660d37',
                    'name' => 'Arma 3 Creator DLC: CSLA Iron Curtain',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'appId' => 1294440,
                    'directory' => 'csla',
                ],
                [
                    'id' => 'c2cd8ffd-0b4b-449b-aca5-cb91f16a9e54',
                    'name' => 'Arma 3 Creator DLC: Global Mobilization - Cold War Germany',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'appId' => 1042220,
                    'directory' => 'gm',
                ],
                [
                    'id' => '805dfa49-ef6b-4259-85c5-a09565174448',
                    'name' => 'Arma 3 Creator DLC: S.O.G. Prairie Fire',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'appId' => 1227700,
                    'directory' => 'vn',
                ],
                [
                    'id' => 'c42adf33-2f16-4bdf-bc38-66d7d037d677',
                    'name' => 'Arma 3 Creator DLC: Spearhead 1944',
                    'createdAt' => '2020-01-01T00:00:00+00:00',
                    'lastUpdatedAt' => null,
                    'appId' => 1175380,
                    'directory' => 'spe',
                ],
            ],
            'id' => 'f3e04dae-18a8-4533-99ea-d6d763ebabcf',
            'name' => 'Default',
            'active' => true,
            'approved' => true,
            'createdAt' => '2020-01-01T00:00:00+00:00',
            'lastUpdatedAt' => null,
        ];
    }
}
