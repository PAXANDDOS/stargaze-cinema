<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class DirectorValidator extends AbstractValidator
{
    protected function getConstraints(): \Symfony\Component\Validator\Constraint
    {
        return new Assert\Collection([
            'name' => [
                $this->assertReqOpt,
                new Assert\Type('string'),
                new Assert\Length(min: 2, max: 64),
            ],
        ]);
    }
}
