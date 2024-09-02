<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\ParseContext;
use Rei\Phod\Message\MessageProvider;

/**
 * @extends \Rei\Phod\PhodSchema<array>
 */
class AssociativeArraySchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param MessageProvider $messageProvider
     * @param array<string, PhodSchema> $schemas
     * @param array{invalid_type_message?: string, required_message?: string} $options
     */
    public function __construct(
        MessageProvider $messageProvider,
        array $schemas,
        array $options = [],
    ) {
        parent::__construct($messageProvider);

        $invalidTypeMessage = $options['invalid_type_message'] ?? $this->messageProvider->get('invalid_type');
        $requiredMessage = $options['required_message'] ?? $this->messageProvider->get('required');

        $this->isArray($invalidTypeMessage)->isValidSchema($schemas, $requiredMessage);
    }

    /**
     * @inheritDoc
    */
    protected function cast(mixed $value): mixed
    {
        if (is_scalar($value) || is_null($value)) {
            return $value;
        }

        return (array) $value;
    }

    /**
     * Rule to check if the value is an array.
     *
     * @param string $message
     * @return static
     */
    private function isArray(string $message): static
    {
        $this->validators[] = function(mixed $value, ParseContext $context) use ($message) {
            if (is_array($value)) {
                return new ParseResult(true, $value);
            }

            return new ParseResult(
                false,
                $value,
                $this->messageProvider->replace($message, ['key' => $context->key, 'type' => 'array']),
            );
        };

        return $this;
    }

    /**
     * Rule to check if the value is valid schema.
     *
     * @param array<string, PhodSchema> $schemas
     * @param string $message
     * @return static
     */
    private function isValidSchema(array $schemas, string $message): static
    {
        $this->validators[] = function(mixed $value, ParseContext $context) use ($schemas, $message) {
            foreach ($schemas as $key => $schema) {
                if (!array_key_exists($key, $value)) {
                    if (!$schema->isOptional()) {
                        return new ParseResult(
                            false,
                            $value,
                            $this->messageProvider->replace($message, ['key' => "the {$key}"]),
                        );
                    }
                } else {
                    $result = $schema->safeParseWithContext($value[$key], new ParseContext($key));

                    if (!$result->succeed) {
                        return $result;
                    }

                    $value[$key] = $result->value;
                }
            }

            return new ParseResult(true, $value);
        };

        return $this;
    }
}
