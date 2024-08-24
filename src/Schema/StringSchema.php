<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

/**
 * @extends \Rei\Phod\PhodSchema<string>
 */
class StringSchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param string $message
     */
    public function __construct(string $message = 'Value must be a string')
    {
        parent::__construct([
            fn($value, $failed) => is_string($value) ?: $failed($message),
        ]);
    }
}
