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
     * @param array<string, PhodSchema> $schemas
     * @param string $message
     */
    public function __construct(array $schemas, string $message = 'Value must be an array')
    {
        $valueValidators = array_merge(...array_map(
            fn($schema, $key) => $this->makeValueValidators($key, $schema),
            $schemas,
            array_keys($schemas),
        ));

        parent::__construct([
            fn($value, $failed) => is_array($value) ?: $failed($message),
            ...$valueValidators,
        ]);
    }

    /**
     * make the value validators
     *
     * @param string $key
     * @param PhodSchema $schema
     * @return array<int, \Closure>
     */
    private function makeValueValidators(string $key, PhodSchema $schema): array
    {
        return array_map(
            fn($validator) => (fn($value, $failed) => $validator($value[$key] ?? null, $failed)),
            $schema->validators(),
        );
    }
}
