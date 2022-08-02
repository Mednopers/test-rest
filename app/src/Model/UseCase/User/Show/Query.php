<?php

namespace App\Model\UseCase\User\Show;

use Symfony\Component\Validator\Constraints as Assert;

class Query
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public readonly string $id
    ) {
    }
}
