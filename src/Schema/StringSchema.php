<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

class StringSchema extends PhodSchema
{
    public function __construct(string $message = 'Value must be a string')
    {
        parent::__construct([
            fn($value, $failed) => is_string($value) ?: $failed($message),
        ]);
    }
}