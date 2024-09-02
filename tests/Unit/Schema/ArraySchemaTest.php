<?php

use Rei\Phod\ParseResult;
use Rei\Phod\Schema\ArraySchema;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\PhodParseFailedException;


describe('parse method', function () {
    it('should throw an exception if any validator returns false', function () {
        $schema = new ArraySchema($this->messageProvider, new StringSchema($this->messageProvider));
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'the value must be array');
    });

    it('should return the value if all validators return true', function () {
        $schema = new ArraySchema($this->messageProvider, new StringSchema($this->messageProvider));
        expect($schema->parse([]))->toBe([]);
    });
});

describe('safeParse method', function () {
    it('should return a ParseResult', function () {
        $schema = new ArraySchema($this->messageProvider, new StringSchema($this->messageProvider));
        expect($schema->safeParse([]))->toBeInstanceOf(ParseResult::class);
    });

    it('should return a ParseResult with the value if all validators return true', function () {
        $schema = new ArraySchema($this->messageProvider, new StringSchema($this->messageProvider));
        $result = $schema->safeParse([]);
        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe([]);
    });

    it('should return a ParseResult with the message if any validator returns false', function () {
        $schema = new ArraySchema($this->messageProvider, new StringSchema($this->messageProvider));
        $result = $schema->safeParse('string');
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('the value must be array');
    });
});
