<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Exception;

use Lihq1403\SdkBase\SdkContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ExceptionProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['exceptionBuilder'] = function (SdkContainer $pimple) {
            return new ExceptionBuilder($pimple);
        };
    }
}
