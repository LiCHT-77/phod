<?php

use InvalidArgumentException;
use Rei\Phod\Schema\LiteralSchema;
use Rei\Phod\Exception\PhodParseFailedException;

describe('parse method', function () {
    it('should return the value if it matches exactly', function () {
        $schema = new LiteralSchema($this->messageProvider, 'test');
        $result = $schema->parse('test');

        expect($result)->toBe('test');
    });

    it('should throw an exception if it does not match', function () {
        $schema = new LiteralSchema($this->messageProvider, 'test');
        expect(fn() => $schema->parse('not_test'))->toThrow(PhodParseFailedException::class, 'the value must be `test`');
    });

    it('should throw an exception if the value is not a literal', function () {
        expect(fn() => new LiteralSchema($this->messageProvider, new stdClass()))->toThrow(InvalidArgumentException::class);
    });

    it('should return the value if it matches an integer', function () {
        $schema = new LiteralSchema($this->messageProvider, 123);
        $result = $schema->parse(123);

        expect($result)->toBe(123);
    });

    it('should throw an exception if it does not match an integer', function () {
        $schema = new LiteralSchema($this->messageProvider, 123);
        expect(fn() => $schema->parse(456))->toThrow(PhodParseFailedException::class, 'the value must be `123`');
    });

    it('should return the value if it matches a boolean', function () {
        $schema = new LiteralSchema($this->messageProvider, true);
        $result = $schema->parse(true);

        expect($result)->toBeTrue();
    });

    it('should throw an exception if it does not match a boolean', function () {
        $schema = new LiteralSchema($this->messageProvider, true);
        expect(fn() => $schema->parse(false))->toThrow(PhodParseFailedException::class, 'the value must be `1`');
    });

    it('should throw an exception if schema is not nullable and given value is null', function () {
        $schema = new LiteralSchema($this->messageProvider, 'test');
        expect(fn() => $schema->parse(null))->toThrow(PhodParseFailedException::class, 'the value must be `test`');
    });

    it('should return the value if schema is nullable and given value is null', function () {
        $schema = new LiteralSchema($this->messageProvider, 'test');
        $result = $schema->nullable()->parse(null);

        expect($result)->toBeNull();
    });
});