<?php

declare(strict_types=1);

namespace App\Validator;

use App\Enum\PEGI;
use Symfony\Component\Validator\Constraints as Assert;

class MovieValidator extends AbstractValidator
{
    protected function getConstraints(): \Symfony\Component\Validator\Constraint
    {
        return new Assert\Collection([
            'title' => [
                $this->assertReqOpt,
                new Assert\Type('string'),
                new Assert\Length(min: 2, max: 64),
            ],
            'synopsis' => new Assert\Optional([new Assert\Length(max: 65535)]),
            'poster' => new Assert\Optional([new Assert\Length(max: 255)]),
            'price' => [
                $this->assertReqOpt,
                new Assert\Type(['integer', 'float']),
                new Assert\Positive(),
            ],
            'year' => [
                $this->assertReqOpt,
                new Assert\GreaterThanOrEqual(1888),
            ],
            'runtime' => [
                $this->assertReqOpt,
                new Assert\GreaterThanOrEqual(30),
            ],
            'rating' => [
                $this->assertReqOpt,
                new Assert\Choice(PEGI::toArray()),
            ],
            'languageId' => [
                $this->assertReqOpt,
                new Assert\Type('integer'),
                new Assert\Positive(),
            ],
            'countryIds' => [
                $this->assertReqOpt,
                new Assert\Type('array'),
            ],
            'genreIds' => [
                $this->assertReqOpt,
                new Assert\Type('array'),
            ],
            'directorIds' => [
                $this->assertReqOpt,
                new Assert\Type('array'),
            ],
        ]);
    }
}
