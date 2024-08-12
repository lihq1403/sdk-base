<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Alarm\Driver;

use GuzzleHttp\RequestOptions;
use Lihq1403\SdkBase\SdkContainer;

class DingDriver implements DriverInterface
{
    private string $host = 'https://oapi.dingtalk.com';

    private SdkContainer $sdkContainer;

    public function __construct(SdkContainer $container)
    {
        $this->sdkContainer = $container;
    }

    public function send(string $receiver, array $body): void
    {
        $uri = $this->getUri($receiver);

        $response = $this->sdkContainer->clientRequest->request('POST', $uri, [
            RequestOptions::JSON => $body,
        ]);
        $content = $response->getBody()->getContents();
        $result = json_decode($content, true);
        if (! isset($result['errcode']) || $result['errcode'] !== 0) {
            $this->sdkContainer->exceptionBuilder->throw(500, "Ding send error: {$content}");
        }
    }

    private function getUri(string $receiver): string
    {
        $config = $this->sdkContainer->config->get('alarm.ding.' . $receiver);
        if (! $config) {
            $this->sdkContainer->exceptionBuilder->throw(500, "Ding config not found: {$receiver}");
        }
        $timestamp = time() * 1000;
        if (empty($config['token']) || empty($config['secret'])) {
            $this->sdkContainer->exceptionBuilder->throw(500, "Ding config error: {$receiver}");
        }

        $secret = hash_hmac('sha256', $timestamp . "\n" . $config['secret'], $config['secret'], true);
        $sign = urlencode(base64_encode($secret));
        return $this->host . "/robot/send?access_token={$config['token']}&timestamp={$timestamp}&sign={$sign}";
    }
}
