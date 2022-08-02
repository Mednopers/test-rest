<?php

namespace App\Infrastructure\Common\Exception\Subscriber;

use App\Infrastructure\Common\ErrorHandler;
use App\Infrastructure\Common\Exception\SymfonyKernelExceptionSubscriber;
use App\Infrastructure\Common\Exception\ValidationException;
use Doctrine\DBAL\Driver\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class DbalDriverExceptionSubscriber extends SymfonyKernelExceptionSubscriber
{
    public function __construct(
        private readonly ErrorHandler $errors,
        private readonly SerializerInterface $serializer
    ) {
    }

    public function supports(\Throwable $throwable, Request $request): bool
    {
        return $throwable instanceof Exception;
    }

    public function processKernelExceptionEvent(ExceptionEvent $event): void
    {
        /** @var ValidationException $throwable */
        $throwable = $event->getThrowable();

        $this->errors->handle($throwable);

        $json = $this->serializer->serialize([
            'code' => Response::HTTP_CONFLICT,
            'message' => 'Try to ask support team.',
        ], JsonEncoder::FORMAT, [
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ]);

        $event->setResponse(new JsonResponse($json, Response::HTTP_CONFLICT, [], true));
    }
}
