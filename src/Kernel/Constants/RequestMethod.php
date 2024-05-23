<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Constants;

class RequestMethod
{
    public const GET = 'get';

    public const POST = 'post';

    public const PUT = 'put';

    public const PATCH = 'patch';

    public const DELETE = 'delete';

    public static function allow(): array
    {
        return [
            self::GET,
            self::POST,
            self::PUT,
            self::PATCH,
            self::DELETE,
        ];
    }
}
