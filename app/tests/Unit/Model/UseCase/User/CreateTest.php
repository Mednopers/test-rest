<?php

namespace App\Tests\Unit\Model\UseCase\User;

use App\Model\UseCase\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Uid\UuidV4;

class CreateTest extends TestCase
{
    public function testSuccessfulUserCreation(): void
    {
        $command = new User\Create\Command();
        $command->name = 'Bob Smith';
        $command->email = 'bob-smith123@gmail.com';

        $handler = new User\Create\Handler(
            $this->createMock(EntityManagerInterface::class),
            $this->createMock(NotifierInterface::class)
        );

        $userId = $handler->handle($command);

        $this->assertTrue(UuidV4::isValid($userId));
    }
}
