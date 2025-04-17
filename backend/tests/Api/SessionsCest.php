<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Hall;
use App\Entity\Movie;
use App\Entity\Session;
use App\Tests\ApiTester;

class SessionsCest
{
    public function tryToGetSession(ApiTester $I): void
    {
        $I->haveInRepository(Session::class, [
            'movie' => $I->grabEntityFromRepository(Movie::class, ['id' => 1]),
            'hall' => $I->grabEntityFromRepository(Hall::class, ['id' => 1]),
            'begin_at' => new \DateTime(),
            'end_at' => new \DateTime('+2 hours'),
        ]);

        $I->sendGet('/sessions');
        $I->seeResponseCodeIsSuccessful();
    }

    public function tryToGetInvalidSession(ApiTester $I): void
    {
        $I->sendGet('/sessions/420');
        $I->seeResponseCodeIsClientError();
    }

    public function tryToPostSession(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/sessions', [
            'movie_id' => 3,
            'hall_id' => 3,
            'begin_at' => (new \DateTime())->format(\DateTime::ISO8601),
            'end_at' => (new \DateTime('+6 hours'))->format(\DateTime::ISO8601),
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToPostInvalidSession(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/sessions', [
            'movie_id' => 3,
            'hall_id' => 3,
            'begin_at' => (new \DateTime())->format(\DateTime::ISO8601),
            'end_at' => (new \DateTime('+5 minutes'))->format(\DateTime::ISO8601),
        ]);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
    }

    public function tryToPatchSession(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $now = new \DateTime();
        $I->haveInRepository(Session::class, [
            'movie' => $I->grabEntityFromRepository(Movie::class, ['id' => 1]),
            'hall' => $I->grabEntityFromRepository(Hall::class, ['id' => 1]),
            'begin_at' => $now,
            'end_at' => new \DateTime('+3 hours'),
        ]);
        $id = $I->grabFromRepository(Session::class, 'id', ['begin_at' => $now]);
        $I->sendPatch("/sessions/$id", [
            'end_at' => (new \DateTime('+4 hours'))->format(\DateTime::ISO8601),
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToDeleteSession(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $now = new \DateTime();
        $I->haveInRepository(Session::class, [
            'movie' => $I->grabEntityFromRepository(Movie::class, ['id' => 1]),
            'hall' => $I->grabEntityFromRepository(Hall::class, ['id' => 1]),
            'begin_at' => $now,
            'end_at' => new \DateTime('+2 hours'),
        ]);
        $id = $I->grabFromRepository(Session::class, 'id', ['begin_at' => $now]);
        $I->sendDelete("/sessions/$id");
        $I->seeResponseCodeIsSuccessful();
    }
}
