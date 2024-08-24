<?php

namespace Rei\Phod\Schema;

use Rei\Phod\PhodSchema;

/**
 * @extends \Rei\Phod\PhodSchema<array>
 */
class ArraySchema extends PhodSchema
{
    /**
     * construct the schema
     *
     * @param array<string, PhodSchema> $data
     * @param string $message
     */
    public function __construct(array $data, string $message = 'Value must be an array')
    {
        parent::__construct([
            fn($value, $failed) => is_array($value) ?: $failed($message),
        ]);
    }
}
