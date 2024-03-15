<?php

declare(strict_types=1);

namespace App\Tests\Functional\Attendances\Api\Attendance;

use App\Attendances\Entity\Attendance\Attendance;
use App\Shared\Service\IdentifierFactory\IdentifierFactoryStub;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;
use Ramsey\Uuid\Uuid;

class CreateAttendanceCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');

        /** @var IdentifierFactoryStub $identifierFactory */
        $identifierFactory = $I->grabService(IdentifierFactoryStub::class);
        $identifierFactory->setIdentifiers([
            Uuid::fromString('805c9fcd-d674-4a27-8f0c-78dbf2484bb2'),
        ]);
    }

    public function createAttendanceWithoutApiKey(FunctionalTester $I): void
    {
        $I->sendPost('/api/attendances', [
            'missionId' => 'mission_99',
            'playerId' => 76561198048200529,
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseContainsJson([
            'detail' => 'Invalid or missing API key provided!',
        ]);

        $I->dontSeeInRepository(Attendance::class, ['missionId' => 'mission_99']);
    }

    public function createAttendanceUsingInvalidApiKey(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('invalid_key');

        $I->sendPost('/api/attendances', [
            'missionId' => 'mission_99',
            'playerId' => 76561198048200529,
        ]);

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
        $I->seeResponseContainsJson([
            'detail' => 'Invalid or missing API key provided!',
        ]);

        $I->dontSeeInRepository(Attendance::class, ['missionId' => 'mission_99']);
    }

    public function createAttendanceUsingApiKey(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('test_key');

        $I->sendPost('/api/attendances', [
            'missionId' => 'mission_99',
            'playerId' => 76561198048200529,
        ]);

        $I->seeResponseCodeIs(HttpCode::CREATED);

        /** @var Attendance $attendance */
        $attendance = $I->grabEntityFromRepository(Attendance::class, ['missionId' => 'mission_99']);
        $I->assertSame('805c9fcd-d674-4a27-8f0c-78dbf2484bb2', $attendance->getId()->toString());
        $I->assertSame('mission_99', $attendance->getMissionId());
        $I->assertSame(76561198048200529, $attendance->getPlayerId());
    }

    public function createAttendanceUsingApiKeyWhenAttendanceAlreadyExist(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('test_key');

        $I->sendPost('/api/attendances', [
            'missionId' => 'mission_1',
            'playerId' => 76561198048200529,
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson([
            'detail' => 'Attendance of player "76561198048200529" in mission "mission_1" already exists.',
        ]);

        /** @var Attendance[] $attendances */
        $attendances = $I->grabEntitiesFromRepository(Attendance::class, ['missionId' => 'mission_1']);
        $I->assertCount(1, $attendances);
    }

    public function createAttendanceUsingApiKeyWithDataTooLong(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('test_key');

        $I->sendPost('/api/attendances', [
            'missionId' => str_repeat('a', 256),
            'playerId' => 76561198048200529,
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson([
            'detail' => 'missionId: This value is too long. It should have 255 characters or less.',
        ]);

        $I->dontSeeInRepository(Attendance::class, ['missionId' => 'mission_99']);
    }

    public function createAttendanceUsingApiKeyWithInvalidPlayerId(FunctionalTester $I): void
    {
        $I->amApiKeyAuthenticatedAs('test_key');

        $I->sendPost('/api/attendances', [
            'missionId' => 'mission_99',
            'playerId' => 123,
        ]);

        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseContainsJson([
            'detail' => 'playerId: Invalid Steam profile ID.',
        ]);

        $I->dontSeeInRepository(Attendance::class, ['missionId' => 'mission_99']);
    }
}
