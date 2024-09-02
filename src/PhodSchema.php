<?php

namespace Rei\Phod;

use Rei\Phod\ParseResult;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\PhodParseFailedException;


/**
 * @template T
 */
class PhodSchema
{
    /**
     * @var string
     */
    protected string $key = 'the value';

    /**
     * construct the schema
     *
     * @param MessageProvider $messageProvider
     * @param array<int, callable(mixed, ParseContext): ParseResult<T>> $validators
     */
    public function __construct(
        protected MessageProvider $messageProvider,
        protected array $validators = [],
    ) {
        //
    }

    /**
     * parse the value
     *
     * @param mixed $value
     * @return T
     */
    public function parse(mixed $value): mixed
    {
        $result = $this->safeParse($value);

        if (!$result->succeed) {
            throw new PhodParseFailedException($result->message);
        }

        return $result->value;
    }

    /**
     * parse the value without throwing exception
     *
     * @param mixed $value
     * @return ParseResult<T>
     */
    public function safeParse(mixed $value): ParseResult
    {
        $context = new ParseContext($this->key);

        return $this->safeParseWithContext($value, $context);
    }

    /**
     * parse the value with context
     *
     * @param mixed $value
     * @param ParseContext $context
     * @return ParseResult<T>
     */
    protected function safeParseWithContext(mixed $value, ParseContext $context): ParseResult
    {
        $value = $this->cast($value);
        foreach ($this->validators as $validator) {
            $result = $validator($value, $context);

            if (!$result->succeed) {
                return $result;
            }
        }

        return new ParseResult(true, $value);
    }

    /**
     * set the key
     *
     * @param string $key
     * @return static
     */
    public function key(string $key): static
    {
        $this->key = $key;

        return $this;
    }


    /**
     * get the message
     *
     * @param array<int, string> $replaces
     */
    protected function message(string $message, array $replaces = []): string
    {
        return strtr($message, array_map(fn($key) => ":$key", array_keys($replaces)));
    }

    /**
     * cast the value
     *
     * @param mixed $value
     * @return T
     */
    protected function cast(mixed $value): mixed
    {
        return $value;
    }
}
