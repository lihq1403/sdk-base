<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Tests;

use GuzzleHttp\Client;
use Lihq1403\SdkBase\Kernel\Components\Alarm\MessageTemplate\DingMarkdown;
use Lihq1403\SdkBase\Kernel\Components\Client\ClientRequest;
use Lihq1403\SdkBase\Kernel\Components\Config\Config;
use Lihq1403\SdkBase\Kernel\Components\Exception\ExceptionBuilder;
use Lihq1403\SdkBase\Kernel\Components\Logger\LoggerProxy;
use Lihq1403\SdkBase\Kernel\Tools\LogCollector;
use Lihq1403\SdkBase\SdkContainer;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class SdkContainerTest extends TestCase
{
    public function testCreate()
    {
        $config = [
            'sdk_name' => 'xxx',
            'exception_class' => BusinessException::class,
            'component' => [
                'logger' => new EchoLogger(),
            ],
        ];

        $app = new SdkContainer($config);

        $this->assertInstanceOf(SdkContainer::class, $app);
        $this->assertInstanceOf(Config::class, $app->config);
        $this->assertInstanceOf(Client::class, $app->client);
        $this->assertInstanceOf(ClientRequest::class, $app->clientRequest);
        $this->assertInstanceOf(LoggerProxy::class, $app->logger);
        $this->assertInstanceOf(ExceptionBuilder::class, $app->exceptionBuilder);
        $this->assertEquals(null, $app->cache);
        $this->assertEquals('xxx', $app->config->getSdkName());
    }

    public function testConfig()
    {
        $config = [
            'sdk_name' => 'xxx',
            'exception_class' => BusinessException::class,
            'component' => [
                'logger' => new EchoLogger(),
            ],
        ];

        $app = new SdkContainer($config);
        $this->assertEquals('xxx', $app->config->getSdkName());
    }

    public function testLogger()
    {
        $sdkName = 'xxx';
        $config = [
            'sdk_name' => $sdkName,
            'exception_class' => BusinessException::class,
            'component' => [
                'logger' => new EchoLogger(),
            ],
        ];

        $app = new SdkContainer($config);
        $app->logger->info('test');
        $this->assertTrue(true);
        $logs = LogCollector::list($sdkName);
        $this->assertEmpty($logs);

        LogCollector::enable();
        $app->logger->info('demo');
        $app->logger->collect('demo1');
        $logs = LogCollector::list($sdkName);
        $this->assertNotEmpty($logs);

        LogCollector::clear();
        $logs = LogCollector::all();
        $this->assertEmpty($logs);
    }

    public function testException()
    {
        $config = [
            'sdk_name' => 'xxx',
            'exception_class' => BusinessException::class,
            'component' => [
                'logger' => new EchoLogger(),
            ],
        ];

        $app = new SdkContainer($config);
        try {
            $app->exceptionBuilder->throw(123, 'test');
        } catch (\Throwable $throwable) {
            $this->assertInstanceOf(BusinessException::class, $throwable);
            $this->assertEquals(123, $throwable->getCode());
            $this->assertEquals('test', $throwable->getMessage());
        }
    }

    public function testClient()
    {
        $config = [
            'sdk_name' => 'xxx',
            'exception_class' => BusinessException::class,
            'component' => [
                'logger' => new EchoLogger(),
            ],
        ];

        $app = new SdkContainer($config);
        $response = $app->client->request('get', 'https://www.baidu.com');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testClientRequest()
    {
        $config = [
            'sdk_name' => 'xxx',
            'exception_class' => BusinessException::class,
            'component' => [
                'logger' => new EchoLogger(),
            ],
        ];

        $app = new SdkContainer($config);
        $response = $app->clientRequest->request('get', 'https://www.baidu.com');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCache()
    {
        $config = [
            'sdk_name' => 'xxx',
            'exception_class' => BusinessException::class,
            'component' => [
                'cache' => new NoCache(),
            ],
        ];

        $app = new SdkContainer($config);
        $this->assertNotNull($app->cache);
        $this->assertFalse($app->cache->has('test'));
    }

    public function testAlarm()
    {
        $this->markTestSkipped('skip test alarm');

        $config = [
            'sdk_name' => 'xxx',
            'exception_class' => BusinessException::class,
            'component' => [
                'cache' => new NoCache(),
            ],
            'alarm' => [
                'ding' => [
                    'default' => [
                        'token' => '123',
                        'secret' => '123',
                    ],
                ],
            ],
        ];
        $app = new SdkContainer($config);

        $message = DingMarkdown::make()
            ->setTitle('测试告警')
            ->setDescription('描述文本')
            ->setContext([
                'key' => 'value',
            ]);
        $app->alarm->send($message);
        $this->assertTrue(true);
    }
}
