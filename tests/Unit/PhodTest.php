<?php

use Rei\Phod\Phod;
use Rei\Phod\PhodSchema;
use Rei\Phod\Schema\StringSchema;

describe('string method', function () {
    it('should return a string schema', function () {
        $phod = new Phod();
        $schema = $phod->string();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(StringSchema::class);
    });
});