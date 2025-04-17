<?php

declare(strict_types=1);

namespace App\Exception;

final class UnauthorizedException extends \DomainException
{
    protected $message;

    public function __construct(string $message = 'You are not authorized to do this action.')
    {
        $this->message = $message;
        parent::__construct();
    }
}
