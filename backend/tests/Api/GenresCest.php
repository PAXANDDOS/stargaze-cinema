<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Genre;
use App\Tests\ApiTester;

class GenresCest
{
    public function tryToGetGenre(ApiTester $I): void
    {
        $I->haveInRepository(Genre::class, [
            'name' => 'Action',
        ]);

        $I->sendGet('/genres');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            [
                'name' => 'Action',
            ],
        ]);
    }

    public function tryToGetInvalidGenre(ApiTester $I): void
    {
        $I->sendGet('/genres/420');
        $I->seeResponseCodeIsClientError();
    }

    public function tryToPostGenre(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/genres', [
            'name' => 'Cringe',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToPostInvalidGenre(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/genres', [
            'name' => true,
        ]);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
    }

    public function tryToPatchGenre(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Genre::class, [
            'name' => 'Disnep',
        ]);
        $id = $I->grabFromRepository(Genre::class, 'id', ['name' => 'Disnep']);
        $I->sendPatch("/genres/$id", [
            'name' => 'Disney',
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            'name' => 'Disney',
        ]);
    }

    public function tryToDeleteGenre(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Genre::class, [
            'name' => 'Delete me',
        ]);
        $id = $I->grabFromRepository(Genre::class, 'id', ['name' => 'Delete me']);
        $I->sendDelete("/genres/$id");
        $I->seeResponseCodeIsSuccessful();
    }
}
