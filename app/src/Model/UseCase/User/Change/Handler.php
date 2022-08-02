<?php

namespace App\Model\UseCase\User\Change;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private readonly ObjectRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function handle(Command $command): string
    {
        $user = $this->repository->find($command->id);
        if (!$user instanceof User) {
            throw new EntityNotFoundException('Not found.');
        }

        $user->change($command->name, $command->email, new \DateTimeImmutable());

        $this->entityManager->flush();

        return $user->getId()->toRfc4122();
    }
}
