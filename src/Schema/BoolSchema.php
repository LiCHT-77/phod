<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\ParseContext;
use Rei\Phod\PhodParseIssue;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\PhodParseFailedException;

/**
 * @extends \Rei\Phod\PhodSchema<bool>
 */
class BoolSchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param MessageProvider $messageProvider
     * @param array{invalid_type_message?: string, required_message?: string} $options
     */
    public function __construct(
        MessageProvider $messageProvider,
        array $options = [],
    ) {
        parent::__construct($messageProvider);

        $this->isBool(isset($options['invalid_type_message']) ? ['message' => $options['invalid_type_message']] : []);
    }

    /**
     * @inheritDoc
     */
    protected function cast(mixed $value): mixed
    {
        $casted = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($casted === null) {
            return $value;
        }

        return $casted;
    }

    /**
     * Rule to check if the value is a boolean.
     *
     * @param array{message?: string} $options
     * @return static
     */
    private function isBool(array $options = []): static
    {
        $message = $options['message'] ?? $this->messageProvider->get('invalid_type');

        $this->validators[] = function(mixed $value, ParseContext $context) use ($message) {
            if (is_bool($value)) {
                return new ParseResult(true, $value);
            }

            return new ParseResult(
                false,
                $value,
                new PhodParseFailedException(
                    new PhodParseIssue(
                        'invalid_type',
                        $context->path,
                        $this->messageProvider->replace($message, ['key' => $context->key, 'type' => 'boolean']),
                    ),
                ),
            );
        };

        return $this;
    }
}
