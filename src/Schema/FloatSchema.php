<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\ParseContext;
use Rei\Phod\Message\MessageProvider;

/**
 * @extends \Rei\Phod\PhodSchema<float>
 */
class FloatSchema extends PhodSchema
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

        $this->isFloat(isset($options['invalid_type_message']) ? ['message' => $options['invalid_type_message']] : []);
    }

    /**
     * @inheritDoc
     */
    protected function cast(mixed $value): mixed
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        return $value;
    }

    /**
     * Rule to check if the value is a float.
     *
     * @param array{message?: string} $options
     * @return static
     */
    private function isFloat(array $options = []): static
    {
        $message = $options['message'] ?? $this->messageProvider->get('invalid_type');

        $this->validators[] = function(mixed $value, ParseContext $context) use ($message) {
            if (is_float($value)) {
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
}
