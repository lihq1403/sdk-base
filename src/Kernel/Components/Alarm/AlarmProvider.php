<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Alarm;

use Lihq1403\SdkBase\SdkContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class AlarmProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['alarm'] = function (SdkContainer $pimple) {
            return new Alarm($pimple);
        };
    }
}
