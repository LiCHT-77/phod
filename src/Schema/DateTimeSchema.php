<?php

namespace Rei\Phod\Schema;

use DateTimeInterface;
use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\PhodParseIssue;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\Exception\PhodParseFailedException;

/**
 * @extends PhodSchema<DateTimeInterface>
 */
class DateTimeSchema extends PhodSchema
{
    /**
     * @param MessageProvider $messageProvider
     * @param array{message?: string} $options
     */
    public function __construct(
        MessageProvider $messageProvider,
        array $options = []
    ) {
        parent::__construct($messageProvider);
        $this->isDateTime(
            isset($options['invalid_type_message']) ? ['message' => $options['invalid_type_message']] : []
        );
    }

    /**
     * Validate that the value is a DateTimeInterface.
     *
     * @param array{message?: string} $options
     * @return self
     */
    public function isDateTime(array $options = []): self
    {
        $message = $options['message'] ?? $this->messageProvider->get('invalid_type');
        $this->validators[] = function ($value, $context) use ($message): ParseResult {
            return $value instanceof DateTimeInterface
                ? new ParseResult(true, $value, null)
                : new ParseResult(
                    false,
                    $value,
                    new PhodParseFailedException(
                        new PhodParseIssue(
                            'invalid_type',
                            $context->path,
                            $this->messageProvider->replace($message, ['key' => $context->key, 'type' => 'DateTimeInterface']),
                        ),
                    )
                );
        };

        return $this;
    }
}