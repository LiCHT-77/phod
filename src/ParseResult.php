<?php

namespace Rei\Phod;

use Rei\Phod\Exception\PhodParseFailedException;

/**
 * @template T
 */
final readonly class ParseResult
{
    /**
     * @param bool $success
     * @param T $value
     * @param PhodParseFailedException|null $exception
     */
    public function __construct(
        public bool $success,
        public mixed $value,
        public ?PhodParseFailedException $exception = null,
    )
    {
        //
    }
}