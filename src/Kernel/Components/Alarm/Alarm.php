<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Alarm;

use Lihq1403\SdkBase\Kernel\Components\Alarm\Driver\DriverInterface;
use Lihq1403\SdkBase\Kernel\Components\Alarm\Driver\Type;
use Lihq1403\SdkBase\Kernel\Components\Alarm\MessageTemplate\MessageInterface;
use Lihq1403\SdkBase\SdkContainer;

/**
 * 简单的告警组件，仅钉钉群消息机器人 markdown ，暂不考虑其他.
 */
class Alarm
{
    /**
     * @var array<string,DriverInterface>
     */
    private array $drivers = [];

    private SdkContainer $sdkContainer;

    public function __construct(SdkContainer $container)
    {
        $this->sdkContainer = $container;
    }

    public function send(MessageInterface $message): void
    {
        $driver = $this->getDriver($message->getType());
        $driver->send($message->getReceiver(), $message->toArray());
    }

    private function getDriver(string $type): DriverInterface
    {
        if (isset($this->drivers[$type])) {
            return $this->drivers[$type];
        }
        $driver = Type::make($this->sdkContainer, $type);
        if ($driver === null) {
            $this->sdkContainer->exceptionBuilder->throw(500, 'Unsupported alarm driver type: ' . $type);
        }
        $this->drivers[$type] = Type::make($this->sdkContainer, $type);
        return $this->drivers[$type];
    }
}
