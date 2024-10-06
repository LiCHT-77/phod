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

    /**
     * extends the current context with a new path
     *
     * @param ParseContext $parent
     * @param array $path
     * @param string $key
     * @return ParseContext
     */
    public static function extends(ParseContext $parent, array $path, string $key): ParseContext
    {
        return new ParseContext([...$parent->path, ...$path], $key);
    }
}