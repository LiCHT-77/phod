<?php

namespace Rei\Phod;

use Rei\Phod\Schema\StringSchema;

class Phod
{
    public function string(): PhodSchema
    {
        return new StringSchema();
    }
}