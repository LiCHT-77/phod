<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

class ArraySchema extends PhodSchema
{
    public function __construct(string $message = 'Value must be an array')
    {
        parent::__construct([
            fn($value, $failed) => is_array($value) ?: $failed($message),
        ]);
    }
}
