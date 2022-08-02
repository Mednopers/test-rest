<?php

namespace App\Model\Common\Validation;

interface ObjectValidationGuardInterface
{
    /**
     * @throws \RuntimeException
     */
    public function validate(object $object): void;
}
