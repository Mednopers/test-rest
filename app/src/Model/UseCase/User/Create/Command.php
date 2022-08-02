<?php

namespace App\Model\UseCase\User\Create;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(title: 'User\Create\Command', required: ['name', 'email'])]
class Command
{
    #[OA\Property(description: 'User name.', example: 'Bob Smith')]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    public string $name;

    #[OA\Property(description: 'User email.', example: 'bob.smith@gmail.com')]
    #[Assert\Email]
    public string $email;
}
