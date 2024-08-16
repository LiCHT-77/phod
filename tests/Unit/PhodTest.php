<?php

use Rei\Phod\Phod;
use Rei\Phod\PhodSchema;
use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\Schema\FloatSchema;
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

describe('int method', function () {
    it('should return an int schema', function () {
        $phod = new Phod();
        $schema = $phod->int();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(IntSchema::class);
    });
});

describe('float method', function () {
    it('should return a float schema', function () {
        $phod = new Phod();
        $schema = $phod->float();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(FloatSchema::class);
    });
});

describe('bool method', function () {
    it('should return a bool schema', function () {
        $phod = new Phod();
        $schema = $phod->bool();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(BoolSchema::class);
    });
});
