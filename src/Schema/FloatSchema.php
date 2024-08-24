<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

/**
 * @extends \Rei\Phod\PhodSchema<float>
 */
class FloatSchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param string $message
     */
    public function __construct(string $message = 'Value must be a float')
    {
        parent::__construct([
            fn($value, $failed) => is_float($value) ?: $failed($message),
        ]);
    }
}
