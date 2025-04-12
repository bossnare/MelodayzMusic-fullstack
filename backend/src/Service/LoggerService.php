<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class LoggerService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function logInfo(string $message): void
    {
        $this->logger->info($message);
    }

    public function logError(string $message): void
    {
        $this->logger->error($message);
    }
}
