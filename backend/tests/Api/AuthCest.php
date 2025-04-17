<?php

declare(strict_types=1);

namespace App\Tests\Api;

use App\Tests\ApiTester;

class AuthCest
{
    public function tryToSignUpInvalidEmail(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/auth/signup', [
            'name' => 'FluffyTester',
            'email' => 'fluffytester@io',
            'password' => 'securepass',
            'password_confirmation' => 'securepass',
        ]);
        $I->seeResponseCodeIsClientError();
    }

    public function tryToSignUpInvalidPassword(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/auth/signup', [
            'name' => 'FluffyTester',
            'email' => 'fluffytester@test.net',
            'password' => 'securepass',
            'password_confirmation' => 'unsecurepass',
        ]);
        $I->seeResponseCodeIsClientError();
    }

    public function tryToSignUp(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/auth/signup', [
            'name' => 'FluffyTester',
            'email' => 'fluffytester@test.net',
            'password' => 'securepass',
            'password_confirmation' => 'securepass',
        ]);
        $I->seeResponseCodeIsSuccessful();
    }

    public function tryToSignIn(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/auth/signin', [
            'email' => 'deeja@stab.com',
            'password' => '123456789',
        ]);
        $I->seeResponseCodeIsSuccessful();
    }
}
