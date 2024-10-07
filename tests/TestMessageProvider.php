<?php

namespace Tests;

use Rei\Phod\Message\MessageProvider;

class TestMessageProvider implements MessageProvider
{
    const MESSAGES = [
        'invalid_type' => ':key must be :type',
        'required' => ':key is required',
        'invalid_literal' => 'the value must be `:literal`',
    ];

    public function get(string $key): string
    {
        return self::MESSAGES[$key] ?? '';
    }

    public function message(string $key, array $params = []): string
    {
        return $this->replace(self::MESSAGES[$key], $params);
    }

    public function replace(string $message, array $params = []): string
    {
        return str_replace(
            array_map(fn ($key) => ":$key", array_keys($params)),
            array_values($params),
            $message
        );
    }
}