<?php

namespace App\Model\UseCase\User\Remove;

use Symfony\Component\Validator\Constraints as Assert;

class Query
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $id
    ) {
    }
}
