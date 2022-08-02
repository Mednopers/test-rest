<?php

namespace App\Infrastructure\Common\Exception\Subscriber;

use App\Infrastructure\Common\Exception\SymfonyKernelExceptionSubscriber;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class DoctrineOrmEntityNotFoundExceptionSubscriber extends SymfonyKernelExceptionSubscriber
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function supports(\Throwable $throwable, Request $request): bool
    {
        return $throwable instanceof EntityNotFoundException;
    }

    public function processKernelExceptionEvent(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $json = $this->serializer->serialize([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => $throwable->getMessage(),
        ], JsonEncoder::FORMAT, [
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ]);

        $event->setResponse(new JsonResponse($json, Response::HTTP_NOT_FOUND, [], true));
    }
}
