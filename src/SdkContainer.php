<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase;

use GuzzleHttp\ClientInterface;
use Lihq1403\SdkBase\Kernel\Components\Alarm\Alarm;
use Lihq1403\SdkBase\Kernel\Components\Alarm\AlarmProvider;
use Lihq1403\SdkBase\Kernel\Components\Cache\CacheProvider;
use Lihq1403\SdkBase\Kernel\Components\Client\ClientProvider;
use Lihq1403\SdkBase\Kernel\Components\Client\ClientRequest;
use Lihq1403\SdkBase\Kernel\Components\Config\Config;
use Lihq1403\SdkBase\Kernel\Components\Exception\ExceptionBuilder;
use Lihq1403\SdkBase\Kernel\Components\Exception\ExceptionProvider;
use Lihq1403\SdkBase\Kernel\Components\Logger\LoggerProvider;
use Lihq1403\SdkBase\Kernel\Components\Logger\LoggerProxy;
use Pimple\Container;
use Psr\SimpleCache\CacheInterface;

/**
 * @property Config $config
 * @property ExceptionBuilder $exceptionBuilder
 * @property LoggerProxy $logger
 * @property ClientInterface $client
 * @property ClientRequest $clientRequest
 * @property ?CacheInterface $cache
 * @property Alarm $alarm
 */
class SdkContainer extends Container
{
    protected array $providers = [];

    /**
     * 这里的顺序有讲究，因为有些服务提供者会依赖其他服务提供者的
     * config 和 exception 放到了最前面.
     */
    protected array $systemProviders = [
        LoggerProvider::class,
        CacheProvider::class,
        ClientProvider::class,
        AlarmProvider::class,
    ];

    public function __construct(array $config = [])
    {
        parent::__construct();

        $this->registerConfig($config);
        $this->registerException();
        $this->registerProviders();
    }

    public function __get(string $id)
    {
        return $this->offsetGet($id);
    }

    public function __set(string $id, $value)
    {
        throw new \RuntimeException('not allow set value');
        //        $this->offsetUnset($id);
        //        $this->offsetSet($id, $value);
    }

    protected function registerProviders()
    {
        $providers = array_merge($this->systemProviders, $this->providers);
        foreach ($providers as $provider) {
            $this->register(new $provider());
        }
    }

    protected function registerConfig(array $config)
    {
        $this['config'] = function () use ($config) {
            return new Config($config);
        };
    }

    protected function registerException()
    {
        $this->register(new ExceptionProvider());
    }
}
