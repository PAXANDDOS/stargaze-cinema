<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Hall;
use App\Enum\HallType;
use App\Tests\ApiTester;

class HallsCest
{
    public function tryToGetHall(ApiTester $I): void
    {
        $I->haveInRepository(Hall::class, [
            'name' => 'Moon',
            'capacity' => 100,
            'type' => HallType::IMAX,
        ]);

        $I->sendGet('/halls');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            [
                'name' => 'Moon',
            ],
        ]);
    }

    public function tryToGetInvalidHall(ApiTester $I): void
    {
        $I->sendGet('/halls/420');
        $I->seeResponseCodeIsClientError();
    }

    public function tryToPostHall(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/halls', [
            'name' => 'Moon',
            'capacity' => 100,
            'type' => 'IMAX 3D',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToPostInvalidHall(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/halls', [
            'name' => 'Moon',
            'type' => 'IMAX',
        ]);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
    }

    public function tryToPatchHall(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Hall::class, [
            'name' => 'Cringe',
            'capacity' => 100,
            'type' => HallType::IMAX,
        ]);
        $id = $I->grabFromRepository(Hall::class, 'id', ['name' => 'Cringe']);
        $I->sendPatch("/halls/$id", [
            'name' => 'Moon',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'name' => 'Moon',
        ]);
    }

    public function tryToDeleteHall(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Hall::class, [
            'name' => 'Delete me',
            'capacity' => 100,
            'type' => HallType::IMAX,
        ]);
        $id = $I->grabFromRepository(Hall::class, 'id', ['name' => 'Delete me']);
        $I->sendDelete("/halls/$id");
        $I->seeResponseCodeIsSuccessful();
    }
}
