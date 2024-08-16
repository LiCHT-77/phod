<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

class FloatSchema extends PhodSchema
{
    public function __construct(string $message = 'Value must be a float')
    {
        parent::__construct([
            fn($value, $failed) => is_float($value) ?: $failed($message),
        ]);
    }
}
