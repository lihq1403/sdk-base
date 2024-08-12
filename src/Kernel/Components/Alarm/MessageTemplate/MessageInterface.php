<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Alarm\MessageTemplate;

interface MessageInterface
{
    public function getType(): string;

    public function getReceiver(): string;

    public function toArray(): array;
}
