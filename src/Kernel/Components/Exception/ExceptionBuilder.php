<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Exception;

use Lihq1403\SdkBase\SdkContainer;

class ExceptionBuilder
{
    private string $exceptionClass;

    public function __construct(SdkContainer $container)
    {
        $this->exceptionClass = $this->getExceptionClass($container);
    }

    public function throw(int $code, string $message = ''): void
    {
        throw new $this->exceptionClass($message, $code);
    }

    private function getExceptionClass(SdkContainer $container): string
    {
        $exceptionClass = $container->config->get('exception_class', \Exception::class);
        if (! class_exists($exceptionClass)) {
            throw new \RuntimeException('Exception Class Not Found');
        }
        if (! is_a($exceptionClass, \Exception::class, true)) {
            throw new \RuntimeException('Exception Class Must Be An Instance Of Exception');
        }
        return $exceptionClass;
    }
}
