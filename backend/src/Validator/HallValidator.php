<?php

declare(strict_types=1);

namespace App\Validator;

use App\Enum\HallType;
use Symfony\Component\Validator\Constraints as Assert;

class HallValidator extends AbstractValidator
{
    protected function getConstraints(): \Symfony\Component\Validator\Constraint
    {
        return new Assert\Collection([
            'name' => [
                $this->assertReqOpt,
                new Assert\Type('string'),
                new Assert\Length(min: 2, max: 16),
            ],
            'capacity' => [
                $this->assertReqOpt,
                new Assert\Type('integer'),
                new Assert\Positive(),
            ],
            'type' => [
                $this->assertReqOpt,
                new Assert\Choice(HallType::toArray()),
            ],
        ]);
    }
}
