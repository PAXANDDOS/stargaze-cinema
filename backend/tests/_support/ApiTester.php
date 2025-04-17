<?php

declare(strict_types=1);

namespace App\Tests;

/**
 * Inherited Methods.
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    private array $credentials = [
        'email' => 'deeja@stab.com',
        'password' => '123456789',
    ];

    public function amBearerAuthorized(array $credentials = null): void
    {
        $this->haveHttpHeader('Accept', 'application/json');
        $this->haveHttpHeader('Content-Type', 'application/json');

        $this->sendPost('/auth/signin', $credentials ?: $this->credentials);
        $response = json_decode($this->grabResponse());

        $this->amBearerAuthenticated($response->token->value);
    }
}
