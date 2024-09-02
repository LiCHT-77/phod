<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\ParseContext;
use Rei\Phod\Message\MessageProvider;

/**
 * @extends \Rei\Phod\PhodSchema<array>
 */
class ArraySchema extends PhodSchema
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

        $message = $options['invalid_type_message'] ?? $this->messageProvider->get('invalid_type');

        $this->isArray($message)->isValidSchema($schemas);
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
                $this->messageProvider->replace($message, ['key' => $context->key]),
            );
        };

        return $this;
    }

    /**
     * Rule to check if the value is valid schema.
     *
     * @param array<string, PhodSchema> $schemas
     * @return static
     */
    private function isValidSchema(array $schemas): static
    {
        $this->validators[] = function(mixed $value, ParseContext $context) use ($schemas) {
            foreach ($value as $key => $item) {
                $schema = $schemas[$key] ?? null;

                if ($schema) {
                    $result = $schema->safeParse($item, new ParseContext($key));

                    if (!$result->succeed) {
                        return $result;
                    }
                }
            }

            return new ParseResult(true, $value);
        };

        return $this;
    }
}
