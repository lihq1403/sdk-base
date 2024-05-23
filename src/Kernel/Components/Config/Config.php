<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Config;

use Adbar\Dot;

class Config extends Dot
{
    public function __construct(array $items = [])
    {
        parent::__construct($items);

        // 检查一些必填项
        if (empty($this->getSdkName())) {
            throw new \InvalidArgumentException('Missing Config: sdk_name');
        }
    }

    public function getSdkName(): string
    {
        return $this->get('sdk_name', '');
    }
}
