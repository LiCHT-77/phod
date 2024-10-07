<?php

use Rei\Phod\Phod;
use Rei\Phod\PhodSchema;
use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\Schema\FloatSchema;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\Schema\DateTimeSchema;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\Schema\AssociativeArraySchema;

beforeEach(function () {
    $this->messageProvider = new class implements MessageProvider {
        public function get(string $key): string
        {
            return 'Value must be an integer';
        }

        public function message(string $key, array $params = []): string
        {
            return 'Value must be an integer';
        }

        public function replace(string $message, array $params = []): string
        {
            return str_replace(array_keys($params), array_values($params), $message);
        }
    };
});

describe('string method', function () {
    it('should return a string schema', function () {
        $phod = new Phod($this->messageProvider);
        $schema = $phod->string();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(StringSchema::class);
    });
});

describe('int method', function () {
    it('should return an int schema', function () {
        $phod = new Phod($this->messageProvider);
        $schema = $phod->int();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(IntSchema::class);
    });
});

describe('float method', function () {
    it('should return a float schema', function () {
        $phod = new Phod($this->messageProvider);
        $schema = $phod->float();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(FloatSchema::class);
    });
});

describe('bool method', function () {
    it('should return a bool schema', function () {
        $phod = new Phod($this->messageProvider);
        $schema = $phod->bool();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(BoolSchema::class);
    });
});

describe('array method', function () {
    it('should return an array schema', function () {
        $phod = new Phod($this->messageProvider);
        $schema = $phod->array([]);
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(AssociativeArraySchema::class);
    });
});

describe('dateTime method', function () {
    it('should return a dateTime schema', function () {
        $phod = new Phod($this->messageProvider);
        $schema = $phod->dateTime();
        expect($schema)
            ->toBeInstanceOf(PhodSchema::class)
            ->toBeInstanceOf(DateTimeSchema::class);
    });
});
