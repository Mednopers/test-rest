<?php

namespace App\Model\UseCase\User\Remove;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ObjectRepository;

class Handler
{
    private ObjectRepository $repository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function handle(Query $command): void
    {
        $user = $this->repository->find($command->id);
        if (!$user instanceof User) {
            throw new EntityNotFoundException('Not found.');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
