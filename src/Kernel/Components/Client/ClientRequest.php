<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Client;

use GuzzleHttp\ClientInterface;
use Lihq1403\SdkBase\Kernel\Components\Config\Config;
use Lihq1403\SdkBase\Kernel\Components\Logger\LoggerProxy;
use Lihq1403\SdkBase\SdkContainer;
use Psr\Http\Message\ResponseInterface;

class ClientRequest
{
    protected ClientInterface $client;

    protected Config $config;

    protected LoggerProxy $logger;

    protected SdkContainer $container;

    public function __construct(SdkContainer $container)
    {
        $this->container = $container;
        $this->config = $container->config;
        $this->logger = $container->logger;
        $this->client = $container->client;
    }

    public function request(string $method, string $uri = '', array $options = []): ResponseInterface
    {
        $parseUrl = parse_url($uri);
        if (! isset($parseUrl['host'])) {
            // 需要有 host，不在 client 中配置 base_url
            $this->container->exceptionBuilder->throw(400, 'Request uri must have host');
        }

        $start = microtime(true);
        $content = '';
        try {
            $response = $this->client->request(strtoupper($method), $uri, $options);
            $content = $response->getBody()->getContents();
            if ($response->getStatusCode() != 200) {
                $this->container->exceptionBuilder->throw((int) $response->getStatusCode(), $content);
            }
            $response->getBody()->rewind();
            return $response;
        } catch (\Throwable $throwable) {
            $this->container->exceptionBuilder->throw((int) $throwable->getCode(), $throwable->getMessage());
        } finally {
            if (isset($throwable)) {
                $content = json_encode([
                    'code' => $throwable->getCode(),
                    'message' => '[bad_request]' . $throwable->getMessage(),
                    'file' => $throwable->getFile(),
                    'line' => $throwable->getLine(),
                ], JSON_UNESCAPED_UNICODE);
            }
            $this->log($method, $uri, $options, $content, $start);
        }
    }

    private function log(string $method, string $uri, array $options, string $content, ?float $startTime = null): void
    {
        $elapsedTime = round((microtime(true) - $startTime) * 1000, 2);
        $this->logger->info(
            $this->config->getSdkName() . '_client_request',
            [
                'method' => $method,
                'uri' => $uri,
                'options' => $options,
                'content' => $content,
                'elapsed_time' => $elapsedTime,
            ]
        );
    }
}
