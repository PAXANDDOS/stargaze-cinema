<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class FrameValidator extends AbstractValidator
{
    protected function getConstraints(): \Symfony\Component\Validator\Constraint
    {
        return new Assert\Collection([
            'movieId' => [
                $this->assertReqOpt,
                new Assert\Type('integer'),
                new Assert\Positive(),
            ],
            'image' => [
                $this->assertReqOpt,
                new Assert\Type('string'),
                new Assert\Length(max: 255),
            ],
        ]);
    }
}
