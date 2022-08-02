<?php

namespace App\Model\UseCase\User\Create;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Uid\UuidV4;

class Handler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NotifierInterface $notifier,
    ) {
    }

    public function handle(Command $command): string
    {
        $user = new User(UuidV4::v4(), $command->name, $command->email, new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->sendAccountCreatedNotificationForUser($user);

        return $user->getId()->toRfc4122();
    }

    private function sendAccountCreatedNotificationForUser(User $user): void
    {
        $notification = (new Notification('User account created.', ['email']))
            ->content('Some text');
        $recipient = (new Recipient($user->getEmail()));
        $this->notifier->send($notification, $recipient);
    }
}
