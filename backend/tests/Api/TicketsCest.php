<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Entity\Session;
use App\Entity\Ticket;
use App\Entity\User;
use App\Tests\ApiTester;

class TicketsCest
{
    public function tryToGetTicket(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->haveInRepository(Ticket::class, [
            'place' => 20,
            'user' => $I->grabEntityFromRepository(User::class, ['id' => 1]),
            'session' => $I->grabEntityFromRepository(Session::class, ['id' => 1]),
        ]);

        $I->sendGet('/tickets');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson([
            [
                'place' => 20,
            ],
        ]);
    }

    public function tryToGetInvalidTicket(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendGet('/tickets/420');
        $I->seeResponseCodeIsClientError();
    }

    public function tryToPostTicket(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/tickets', [
            'place' => 1,
            'user_id' => 3,
            'session_id' => 3,
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToPostInvalidTicket(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $I->sendPost('/tickets', [
            'place' => 500,
            'user_id' => 3,
            'session_id' => 3,
        ]);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseIsJson();
    }

    public function tryToPatchTicket(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $data = [
            'place' => 20,
            'user' => $I->grabEntityFromRepository(User::class, ['id' => 1]),
            'session' => $I->grabEntityFromRepository(Session::class, ['id' => 1]),
        ];
        $I->haveInRepository(Ticket::class, $data);
        $id = $I->grabFromRepository(Ticket::class, 'id', $data);
        $I->sendPatch("/tickets/$id", [
            'place' => 5,
        ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    public function tryToDeleteTicket(ApiTester $I): void
    {
        $I->amBearerAuthorized();
        $data = [
            'place' => 20,
            'user' => $I->grabEntityFromRepository(User::class, ['id' => 1]),
            'session' => $I->grabEntityFromRepository(Session::class, ['id' => 1]),
        ];
        $I->haveInRepository(Ticket::class, $data);
        $id = $I->grabFromRepository(Ticket::class, 'id', $data);
        $I->sendDelete("/tickets/$id");
        $I->seeResponseCodeIsSuccessful();
    }
}
