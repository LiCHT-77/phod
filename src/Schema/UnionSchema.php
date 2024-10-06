<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\ParseContext;
use Rei\Phod\PhodParseIssue;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\Exception\PhodParseFailedException;
use Rei\Phod\Exception\PhodUnionParseFailedException;

/**
 * @extends \Rei\Phod\PhodSchema<mixed>
 */
class UnionSchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param MessageProvider $messageProvider
     * @param PhodSchema[] $schemas
     */
    public function __construct(MessageProvider $messageProvider, array $schemas)
    {
        parent::__construct($messageProvider);
        $this->isValidUnion($schemas);
    }

    /**
     * Rule to check if the value matches any of the schemas.
     *
     * @param PhodSchema[] $schemas
     * @return static
     */
    private function isValidUnion(array $schemas): static
    {
        $this->validators[] = function(mixed $value, ParseContext $context) use ($schemas) {
            $errors = [];
            foreach ($schemas as $schema) {
                $result = $schema->safeParseWithContext($value, $context);
                if ($result->success) {
                    return $result;
                }
                $errors[] = $result->exception;
            }

            return new ParseResult(
                false,
                $value,
                new PhodUnionParseFailedException(
                    $errors,
                    new PhodParseIssue(
                        'invalid_type',
                        $context->path,
                        'Value must match one of the schemas',
                    ),
                ),
            );
        };

        return $this;
    }
}