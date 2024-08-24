<?php

namespace Rei\Phod;


/**
 * @template T
 */
class PhodSchema
{
    public function __construct(
        protected array $validators = [],
    )
    {
        //
    }

    /**
     * refine the schema
     *
     * @param callable $callable
     * @return PhodSchema
     */
    public function refine(callable $callable): PhodSchema
    {
        return new static([
            ...$this->validators,
            $callable,
        ]);
    }

    /**
     * parse the value
     *
     * @param mixed $value
     * @return T
     */
    public function parse(mixed $value): mixed
    {
        $failed = fn (string $message) => throw new PhodParseFailedException($message);

        foreach ($this->validators as $validator) {
            $validator($value, $failed);
        }

        return $value;
    }

    /**
     * parse the value without throwing exception
     *
     * @param mixed $value
     * @return ParseResult<T>
     */
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