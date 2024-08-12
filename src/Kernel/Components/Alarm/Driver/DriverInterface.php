<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Alarm\Driver;

interface DriverInterface
{
    public function send(string $receiver, array $body): void;
}
