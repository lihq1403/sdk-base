<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Client;

use GuzzleHttp\ClientInterface;
use Lihq1403\SdkBase\SdkContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ClientProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['client'] = function (SdkContainer $pimple) {
            if ($client = $pimple->config->get('component.client')) {
                if ($client instanceof ClientInterface) {
                    return $client;
                }
            }
            return (new ClientFactory())();
        };

        $pimple['clientRequest'] = function (SdkContainer $pimple) {
            return new ClientRequest($pimple);
        };
    }
}
