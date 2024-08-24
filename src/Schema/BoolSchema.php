<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

/**
 * @extends \Rei\Phod\PhodSchema<bool>
 */
class BoolSchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param string $message
     */
    public function __construct(string $message = 'Value must be a boolean')
    {
        parent::__construct([
            fn($value, $failed) => is_bool($value) ?: $failed($message),
        ]);
    }
}
