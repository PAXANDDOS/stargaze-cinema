<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Director;
use App\Tests\ApiTester;

class DirectorsCest
{
    public function tryToGetDirector(ApiTester $I): void
    {
        $I->haveInRepository(Director::class, [
            'name' => 'Ryan Raynolds',
        ]);

        $I->sendGet('/directors');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            [
                'name' => 'Ryan Raynolds',
            ],
        ]);
    }

    public function tryToGetInvalidDirector(ApiTester $I): void
    {
        $I->sendGet('/directors/420');
        $I->seeResponseCodeIsClientError();
    }

    public function tryToPostDirector(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/directors', [
            'name' => 'Ryan Raynolds',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToPostInvalidDirector(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/directors', [
            'name' => 422,
        ]);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
    }

    public function tryToPatchDirector(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Director::class, [
            'name' => 'Ryan Raynolds',
        ]);
        $id = $I->grabFromRepository(Director::class, 'id', ['name' => 'Ryan Raynolds']);
        $I->sendPatch("/directors/$id", [
            'name' => 'Star Raynolds',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'name' => 'Star Raynolds',
        ]);
    }

    public function tryToDeleteDirector(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Director::class, [
            'name' => 'Ryan Raynolds',
        ]);
        $id = $I->grabFromRepository(Director::class, 'id', ['name' => 'Ryan Raynolds']);
        $I->sendDelete("/directors/$id");
        $I->seeResponseCodeIsSuccessful();
    }
}
