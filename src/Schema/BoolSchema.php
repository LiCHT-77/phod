<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

class BoolSchema extends PhodSchema
{
    public function __construct(string $message = 'Value must be a boolean')
    {
        parent::__construct([
            fn($value, $failed) => is_bool($value) ?: $failed($message),
        ]);
    }
}