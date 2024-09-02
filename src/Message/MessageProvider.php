<?php

namespace Rei\Phod\Message;

interface MessageProvider
{
    public function get(string $key): string;

    /**
     * get the message
     *
     * @param array<int, string> $params
     */
    public function message(string $key, array $params = []): string;

    /**
     * get the message with replaced params
     *
     * @param array<int, string> $params
     */
    public function replace(string $message, array $params = []): string;
}