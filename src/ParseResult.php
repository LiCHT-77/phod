<?php

namespace Rei\Phod;

final readonly class ParseResult
{
    public function __construct(
        public bool $succeed,
        public mixed $value,
        public string $message = '',
    )
    {
        //
    }
}