<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;

class UserValidator extends AbstractValidator
{
    protected function getConstraints(): \Symfony\Component\Validator\Constraint
    {
        return new Assert\Collection([
            'name' => [
                $this->assertReqOpt,
                new Assert\Length(min: 2, max: 32),
            ],
            'email' => [
                $this->assertReqOpt,
                new Assert\Email(),
                new Assert\Length(min: 2, max: 128),
            ],
            'roles' => [
                $this->assertReqOpt,
                new Assert\Type('array'),
            ],
            'password' => [
                $this->assertReqOpt,
                new Assert\Length(min: 8, max: 255),
            ],
            'passwordConfirmation' => [
                $this->assertReqOpt,
                new Assert\Length(min: 8, max: 255),
            ],
        ]);
    }

    public function validateSignIn(array $params)
    {
        return $this->parseViolations($this->validator->validate(
            $params,
            new Assert\Collection([
                'email' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(min: 2, max: 128),
                ],
                'password' => [
                    new Assert\NotBlank(),
                    new Assert\Length(min: 8, max: 255),
                ],
            ])
        ));
    }

    public function validateSignUp(array $params)
    {
        return $this->parseViolations($this->validator->validate(
            $params,
            new Assert\Collection([
                'name' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                    new Assert\Length(min: 2, max: 32),
                ],
                'email' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Type('string'),
                    new Assert\Length(min: 2, max: 128),
                ],
                'password' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                    new Assert\Length(min: 8, max: 255),
                ],
                'passwordConfirmation' => [
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                    new Assert\Length(min: 8, max: 255),
                ],
            ])
        ));
    }
}
