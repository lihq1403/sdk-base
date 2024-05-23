<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Cache;

use Lihq1403\SdkBase\SdkContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\SimpleCache\CacheInterface;

class CacheProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['cache'] = function (SdkContainer $pimple) {
            if ($cache = $pimple->config->get('component.cache')) {
                if ($cache instanceof CacheInterface) {
                    return $cache;
                }
            }
            return null;
        };
    }
}
