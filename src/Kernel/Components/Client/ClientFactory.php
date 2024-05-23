<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Client;

use GuzzleHttp\Client;

class ClientFactory
{
    public function __invoke(array $options = []): Client
    {
        return new Client($options);
    }
}
