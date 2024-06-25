<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Logger;

use Lihq1403\SdkBase\Kernel\Tools\LogCollector;
use Lihq1403\SdkBase\SdkContainer;
use Psr\Log\LoggerInterface;

/**
 * 因为psr/log 1.0和2.0、3.0有差异，就不继承使用了，不直接注入，这里做一个转发.
 * @method void emergency(string $message, array $context = [])
 * @method void alert(string $message, array $context = [])
 * @method void critical(string $message, array $context = [])
 * @method void error(string $message, array $context = [])
 * @method void warning(string $message, array $context = [])
 * @method void notice(string $message, array $context = [])
 * @method void info(string $message, array $context = [])
 * @method void debug(string $message, array $context = [])
 * @method void collect(string $message, array $context = [])
 */
class LoggerProxy
{
    protected ?LoggerInterface $logger = null;

    private SdkContainer $sdkContainer;

    public function __construct(SdkContainer $container)
    {
        $this->sdkContainer = $container;

        $logger = $container->config->get('component.logger');
        if ($logger) {
            if ($logger instanceof LoggerInterface) {
                $this->logger = $logger;
            } else {
                if (is_string($logger) && class_exists($logger)) {
                    $implements = class_implements($logger);
                    if (in_array(LoggerInterface::class, $implements)) {
                        $this->logger = new $logger();
                    }
                }
            }
        }
    }

    public function __call($name, $arguments)
    {
        LogCollector::set($this->sdkContainer->config->getSdkName(), ...$arguments);
        if ($name === 'collect') {
            // 仅收集
            return;
        }
        if ($this->logger && method_exists($this->logger, $name)) {
            $this->logger->{$name}(...$arguments);
        }
    }
}
