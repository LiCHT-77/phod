<?php

namespace Rei\Phod;

readonly class PhodParseIssue
{
    /**
     * construct the issue
     *
     * @param string $code
     * @param (int|string)[] $path
     * @param string $message
     * @param array<string, mixed> $properties
     */
    public function __construct(
        public string $code,
        public array $path,
        public string $message,
        public array $properties = [],
    ) {
        //
    }
}
