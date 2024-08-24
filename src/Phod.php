<?php

namespace Rei\Phod;

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\Schema\ArraySchema;
use Rei\Phod\Schema\FloatSchema;
use Rei\Phod\Schema\StringSchema;


class Phod
{
    /**
     * Make a StringSchema.
     *
     * @return StringSchema
     */
    public function string(): StringSchema
    {
        return new StringSchema();
    }

    /**
     * Make an IntSchema.
     *
     * @return IntSchema
     */
    public function int(): IntSchema
    {
        return new IntSchema();
    }

    /**
     * Make a FloatSchema.
     *
     * @return FloatSchema
     */
    public function float(): FloatSchema
    {
        return new FloatSchema();
    }

    /**
     * Make a BoolSchema.
     *
     * @return BoolSchema
     */
    public function bool(): BoolSchema
    {
        return new BoolSchema();
    }

    /**
     * Make an ArraySchema.
     *
     * @param array<string, PhodSchema> $data
     * @return ArraySchema
     */
    public function array(array $data): ArraySchema
    {
        return new ArraySchema($data);
    }
}
