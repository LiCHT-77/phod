<?php

namespace Rei\Phod;


class PhodSchema
{
    public function __construct(
        protected array $validators = [],
    )
    {
        //
    }

    public function refine(callable $callable): PhodSchema
    {
        return new static([
            ...$this->validators,
            $callable,
        ]);
    }

    public function parse(mixed $value): mixed
    {
        $failed = fn (string $message) => throw new PhodParseFailedException($message);

        foreach ($this->validators as $validator) {
            $validator($value, $failed);
        }

        return $value;
    }

    public function safeParse(mixed $value): ParseResult
    {
        $failed = fn ($message) => new ParseResult(false, $value, $message);

        foreach ($this->validators as $validator) {
            $result = $validator($value, $failed);
            if ($result instanceof ParseResult) {
                return $result;
            }
        }

        return new ParseResult(true, $value);
    }
}