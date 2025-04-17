<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class TicketValidator extends AbstractValidator
{
    protected function getConstraints(): \Symfony\Component\Validator\Constraint
    {
        return new Assert\Collection([
            'place' => [
                $this->assertReqOpt,
                new Assert\Type('integer'),
                new Assert\Positive(),
            ],
            'userId' => [
                $this->assertReqOpt,
                new Assert\Type('integer'),
                new Assert\Positive(),
            ],
            'sessionId' => [
                $this->assertReqOpt,
                new Assert\Type('integer'),
                new Assert\Positive(),
            ],
        ]);
    }
}
