<?php

namespace Rei\Phod;

use Rei\Phod\ParseResult;
use Rei\Phod\Message\MessageProvider;


/**
 * @template T
 */
class PhodSchema
{
    private const UNSET = '__UNSET__';

    /**
     * @var string|null
     */
    protected ?string $key = null;

    /**
     * @var bool
     */
    protected bool $optional = false;

    /**
     * @var bool
     */
    protected bool $nullable = false;

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

        if (!$result->success) {
            throw $result->exception;
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
        return $this->safeParseWithContext($value, null);
    }

    /**
     * parse the value with context
     *
     * @param mixed $value
     * @param ParseContext|null $context
     * @return ParseResult<T>
     */
    protected function safeParseWithContext(mixed $value, ?ParseContext $context): ParseResult
    {
        $context = $context
            ? ParseContext::extends($context, [], $this->key ?? $context->key)
            : new ParseContext([], $this->key ?? 'the value');

        $value = $this->cast($value);

        if ($this->nullable && is_null($value)) {
            return new ParseResult(true, $value);
        }

        foreach ($this->validators as $validator) {
            $result = $validator($value, $context);

            if (!$result->success) {
                return $result;
            }
        }

        return new ParseResult(true, $result->value);
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
     * get the key
     *
     * @return string|null
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * set the value to be optional
     *
     * @return static
     */
    public function optional(): static
    {
        $this->optional = true;

        return $this;
    }

    /**
     * check if the value is optional
     *
     * @return bool
     */
    public function isOptional(): bool
    {
        return $this->optional;
    }

    /**
     * set the value to be nullable
     *
     * @return static
     */
    public function nullable(): static
    {
        $this->nullable = true;

        return $this;
    }

    /**
     * refine the schema
     *
     * @param callable(mixed, ParseContext): ParseResult<T> $validator
     * @return static
     */
    public function refine(callable $validator): static
    {
        $this->validators[] = $validator;

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
