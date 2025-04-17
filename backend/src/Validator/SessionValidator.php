<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class SessionValidator extends AbstractValidator
{
    protected function getConstraints(): \Symfony\Component\Validator\Constraint
    {
        $dateTimeISO = new Assert\Regex(
            pattern: '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.\d+)?(Z|[+-]\d{4})$/',
            message: 'This value {{ value }} is not a valid DateTime, expected ISO string.'
        );

        return new Assert\Collection([
            'beginAt' => [
                $this->assertReqOpt,
                new Assert\Type('string'),
                $dateTimeISO,
            ],
            'endAt' => [
                $this->assertReqOpt,
                new Assert\Type('string'),
                $dateTimeISO,
            ],
            'movieId' => [
                $this->assertReqOpt,
                new Assert\Type('integer'),
                new Assert\Positive(),
            ],
            'hallId' => [
                $this->assertReqOpt,
                new Assert\Type('integer'),
                new Assert\Positive(),
            ],
        ]);
    }
}
