<?php

namespace App\Model\UseCase\User\Show;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class Handler
{
    private readonly ObjectRepository $repository;

    public function __construct(EntityManagerInterface $entityManager, private readonly NormalizerInterface $normalizer)
    {
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function handle(Query $query)
    {
        $user = $this->repository->find($query->id);
        if (!$user instanceof User) {
            throw new EntityNotFoundException('Not found.');
        }

        return $this->normalizer->normalize($user);
    }
}
