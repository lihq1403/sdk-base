<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Alarm\Driver;

use Lihq1403\SdkBase\SdkContainer;

class Type
{
    public const DING = 'ding';

    public static function make(SdkContainer $container, string $type): ?DriverInterface
    {
        switch ($type) {
            case self::DING:
                return new DingDriver($container);
            default:
                return null;
        }
    }
}
