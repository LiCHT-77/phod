<?php

namespace Rei\Phod;

/**
 * @template T
 */
final readonly class ParseResult
{
    /**
     * @param bool $succeed
     * @param T $value
     * @param string $message
     */
    public function __construct(
        public bool $succeed,
        public mixed $value,
        public string $message = '',
    )
    {
        //
    }
}