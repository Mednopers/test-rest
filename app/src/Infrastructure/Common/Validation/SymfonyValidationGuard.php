<?php

namespace App\Infrastructure\Common\Validation;

use App\Infrastructure\Common\Exception\ValidationException;
use App\Model\Common\Validation\ObjectValidationGuardInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SymfonyValidationGuard implements ObjectValidationGuardInterface
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function validate(object $object): void
    {
        $violations = $this->validator->validate($object);
        if (\count($violations) > 0) {
            throw new ValidationException($violations);
        }
    }
}
