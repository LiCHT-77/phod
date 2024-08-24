<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

class IntSchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param string $message
     */
    public function __construct(string $message = 'Value must be an integer')
    {
        parent::__construct([
            fn($value, $failed) => is_int($value) ?: $failed($message),
        ]);
    }
}
