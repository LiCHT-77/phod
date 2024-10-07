<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\ParseContext;
use Rei\Phod\PhodParseIssue;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\Exception\PhodParseFailedException;

/**
 * @extends \Rei\Phod\PhodSchema<array>
 */
class ArraySchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param MessageProvider $messageProvider
     * @param PhodSchema $schema
     * @param array{invalid_type_message?: string} $options
     */
    public function __construct(MessageProvider $messageProvider, PhodSchema $schema, array $options = [])
    {
        parent::__construct($messageProvider);

        $this->isArray(['message' => $options['invalid_type_message'] ?? $messageProvider->get('invalid_type')])
            ->isValidElements($schema);
    }

    /**
     * Rule to check if the value is an array.
     *
     * @param array{message: string} $options
     * @return static
     */
    private function isArray(array $options): static
    {
        $this->validators[] = function(mixed $value, ParseContext $context) use ($options) {
            if (is_array($value)) {
                return new ParseResult(true, $value);
            }

            return new ParseResult(
                false,
                $value,
                new PhodParseFailedException(
                    new PhodParseIssue(
                        'invalid_type',
                        $context->path,
                        $this->messageProvider->replace($options['message'], ['key' => $context->key, 'type' => 'array']),
                    ),
                ),
            );
        };

        return $this;
    }

    /**
     * Rule to check if the value is valid schema.
     *
     * @param PhodSchema $schema
     * @return static
     */
    private function isValidElements(PhodSchema $schema): static
    {
        $this->validators[] = function(mixed $value, ParseContext $context) use ($schema) {
            foreach ($value as $key => $element) {
                $result = $schema->safeParseWithContext($element, new ParseContext([...$context->path, $key], $key));
                if (!$result->success) {
                    return $result;
                }
            }

            return new ParseResult(true, $value);
        };

        return $this;
    }
}
