<?php

declare(strict_types=1);
/**
 * This file is part of Lihq1403.
 */

namespace Lihq1403\SdkBase\Kernel\Components\Alarm\MessageTemplate;

use Lihq1403\SdkBase\Kernel\Components\Alarm\Driver\Type;

class DingMarkdown implements MessageInterface
{
    private string $title;

    private string $description;

    private array $context = [];

    private string $level = '';

    private string $item = '';

    private string $time = '';

    private string $receiver;

    private function __construct(string $receiver)
    {
        $this->receiver = $receiver;
    }

    public static function make(string $receiver = 'default'): self
    {
        return new self($receiver);
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setContext(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    public function setLevel(string $level): self
    {
        $this->level = $level;
        return $this;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;
        return $this;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;
        return $this;
    }

    public function getType(): string
    {
        return Type::DING;
    }

    public function getReceiver(): string
    {
        return $this->receiver;
    }

    public function toArray(): array
    {
        $context = json_encode($this->context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (empty($this->time)) {
            $this->time = date('Y-m-d H:i:s');
        }

        return [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $this->title,
                'text' => <<<MARKDOWN
## {$this->title}
{$this->description}

> 触发时间: {$this->time}

> 触发级别: {$this->level}

> 告警项目: {$this->item}

## 上下文
> {$context}
MARKDOWN,
            ],
        ];
    }
}
