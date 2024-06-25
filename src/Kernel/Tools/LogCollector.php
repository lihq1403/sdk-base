<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Tools;

class LogCollector
{
    protected static array $enables;

    protected static array $logs = [];

    public static function set(string $key, string $message, array $context = []): void
    {
        if (! (self::$enables[$key] ?? false)) {
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

    public static function clear(string $key): void
    {
        self::$logs[$key] = [];
    }

    public static function enable(string $key): void
    {
        self::$enables[$key] = true;
    }

    public static function disable(string $key): void
    {
        self::$enables[$key] = false;
    }
}
