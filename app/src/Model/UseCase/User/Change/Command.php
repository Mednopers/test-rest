<?php

namespace App\Model\UseCase\User\Change;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(title: 'User\Change\Command', required: ['id', 'name', 'email'])]
class Command
{
    #[OA\Property(description: 'User id.')]
    #[Assert\Uuid]
    #[Assert\NotBlank]
    public string $id;

    #[OA\Property(description: 'User name.')]
    #[Assert\NotBlank]
    #[Assert\Type(type: 'string')]
    public string $name;

    #[OA\Property(description: 'User email.')]
    #[Assert\Email]
    public string $email;
}
