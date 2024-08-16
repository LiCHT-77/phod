<?php

namespace Rei\Phod;

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\Schema\FloatSchema;
use Rei\Phod\Schema\StringSchema;

class Phod
{
    public function string(): StringSchema
    {
        return new StringSchema();
    }

    public function int(): IntSchema
    {
        return new IntSchema();
    }

    public function float(): FloatSchema
    {
        return new FloatSchema();
    }

    public function bool(): BoolSchema
    {
        return new BoolSchema();
    }
}
