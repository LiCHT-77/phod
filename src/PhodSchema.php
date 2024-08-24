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
     * get the validators
     *
     * @return array<int, callable>
     */
    public function validators(): array
    {
        return $this->validators;
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
     * get the failed function for parse
     *
     * @return \Closure
     */
    private function getFailedForParse(): \Closure
    {
        return fn (string $message) => throw new PhodParseFailedException($message);
    }

    /**
     * get the failed function for safe parse
     *
     *
     * @return \Closure
     */
    private function getFailedForSafeParse(mixed $value): \Closure
    {
        return fn ($message) => new ParseResult(false, $value, $message);
    }

    /**
     * parse the value
     *
     * @param mixed $value
     * @return T
     */
    public function parse(mixed $value): mixed
    {
        $failed = $this->getFailedForParse();

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
        $failed = $this->getFailedForSafeParse($value);

        foreach ($this->validators as $validator) {
            $result = $validator($value, $failed);
            if ($result instanceof ParseResult) {
                return $result;
            }
        }

        return new ParseResult(true, $value);
    }
}