<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    protected readonly Constraint $assertReqOpt;

    public function __construct(
        protected ValidatorInterface $validator
    ) {
    }

    abstract protected function getConstraints(): Constraint;

    final protected function parseViolations(ConstraintViolationListInterface $violations): ?JsonResponse
    {
        $errorResponse = null;

        if (count($violations) > 0) {
            $errorMessage = [];
            foreach ($violations as $error) {
                array_push($errorMessage, [$error->getPropertyPath() => $error->getMessage()]);
            }
            $errorResponse = new JsonResponse(['message' => $errorMessage], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $errorResponse;
    }

    final public function validate(array $params, bool $isPatch = false): ?JsonResponse
    {
        $this->assertReqOpt = $isPatch ? new Assert\Optional() : new Assert\NotBlank();

        $violations = $this->validator->validate($params, $this->getConstraints());

        return $this->parseViolations($violations);
    }
}
