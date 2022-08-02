<?php

namespace App\Infrastructure\Common;

use Psr\Log\LoggerInterface;

class ErrorHandler
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function handle(\Throwable $throwable): void
    {
        $this->logger->warning($throwable->getMessage(), [
            'exception' => $throwable,
        ]);
    }
}
