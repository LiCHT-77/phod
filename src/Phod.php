<?php

namespace Rei\Phod;

use Rei\Phod\Schema\IntSchema;
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
}
