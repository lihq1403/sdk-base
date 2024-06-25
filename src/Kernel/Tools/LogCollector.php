<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Tools;

class LogCollector
{
    /**
     * 全局开关.
     */
    protected static bool $enabled = false;

    /**
     * 部分开关.
     */
    protected static array $enables;

    protected static array $logs = [];

    public static function set(string $key, string $message, array $context = []): void
    {
        // 部分开关优先于全局开关，全局开关用作默认值就行
        if (! (self::$enables[$key] ?? self::$enabled)) {
            return;
        }

        self::$logs[$key][] = [
            'message' => $message,
            'context' => $context,
        ];
    }

    public static function all(): array
    {
        return self::$logs;
    }

    public static function list(string $key): array
    {
        return self::$logs[$key] ?? [];
    }

    public static function clear(string $key = ''): void
    {
        empty($key) ? self::$logs = [] : self::$logs[$key] = [];
    }

    public static function enable(string $key = ''): void
    {
        empty($key) ? self::$enabled = true : self::$enables[$key] = true;
    }

    public static function disable(string $key = ''): void
    {
        empty($key) ? self::$enabled = false : self::$enables[$key] = false;
    }
}
