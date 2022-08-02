<?php

namespace App\Infrastructure\Common\Exception;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

abstract class SymfonyKernelExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    final public function onKernelException(ExceptionEvent $event): void
    {
        if (! $this->supports($event->getThrowable(), $event->getRequest())) {
            return;
        }

        $this->processKernelExceptionEvent($event);
    }

    abstract public function supports(\Throwable $throwable, Request $request): bool;

    abstract public function processKernelExceptionEvent(ExceptionEvent $event): void;
}
