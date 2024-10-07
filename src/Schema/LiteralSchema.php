<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\ParseContext;
use Rei\Phod\PhodParseIssue;
use InvalidArgumentException;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\Exception\PhodParseFailedException;

/**
 * @template K
 * @extends \Rei\Phod\PhodSchema<K>
 */
class LiteralSchema extends PhodSchema
{
    /**
     * @param MessageProvider $messageProvider
     * @param K $literalValue
     * @param array{invalid_literal_message?: string} $options
     */
    public function __construct(
        MessageProvider $messageProvider,
        mixed $literalValue,
        array $options = []
    ) {
        if (is_object($literalValue)) {
            throw new InvalidArgumentException('Literal value must be not an object');
        }

        parent::__construct($messageProvider);
        $this->isEqualToLiteral(
            $literalValue,
            isset($options['invalid_literal_message']) ? ['message' => $options['invalid_literal_message']] : []
        );
    }

    /**
     * @inheritDoc
     */
    protected function cast(mixed $value): mixed
    {
        return $value;
    }

    /**
     * @param array{message?: string} $options
     */
    protected function isEqualToLiteral(mixed $literal, array $options = []): static
    {
        $message = $options['message'] ?? $this->messageProvider->get('invalid_literal');

        $this->validators[] = function (mixed $value, ParseContext $context) use ($literal, $message): ParseResult {
            return $value === $literal
                ? new ParseResult(true, $value, null)
                : new ParseResult(false, $value, new PhodParseFailedException(
                    new PhodParseIssue(
                        'invalid_literal',
                        $context->path,
                        $this->messageProvider->replace(
                            $message,
                            [
                                'literal' => $literal,
                            ]
                        )
                    )
                ));
        };

        return $this;
    }
}
