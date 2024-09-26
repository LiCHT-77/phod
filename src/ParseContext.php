<?php

namespace Rei\Phod;

readonly class ParseContext
{
    public function __construct(
        public array $path,
        public string $key,
    )
    {
        //
    }
}