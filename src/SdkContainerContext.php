<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase;

class SdkContainerContext
{
    private static array $containers = [];

    public static function getSdkContainer(string $key): SdkContainer
    {
        $container = self::$containers[$key] ?? null;
        if (! $container instanceof SdkContainer) {
            throw new \RuntimeException("{$key} is not registered");
        }
        return $container;
    }

    public static function register(string $key, SdkContainer $container): void
    {
        if (isset(self::$containers[$key])) {
            throw new \RuntimeException("{$key} is already registered");
        }
        self::$containers[$key] = $container;
    }
}
