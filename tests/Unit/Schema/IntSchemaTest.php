<?php

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\PhodParseFailedException;


describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new IntSchema($this->messageProvider);
        $result = $schema->parse(123);
        expect($result)->toBe(123);
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new IntSchema($this->messageProvider);
        expect(fn() => $schema->parse('value'))->toThrow(PhodParseFailedException::class, 'the value must be integer');
        expect(fn() => $schema->parse(2.2))->toThrow(PhodParseFailedException::class, 'the value must be integer');
    });

    it('should cast the value to an integer', function () {
        $schema = new IntSchema($this->messageProvider);
        expect($schema->parse('123'))->toBe(123);
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new IntSchema($this->messageProvider);
        $result = $schema->safeParse(123);
        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not an integer', function () {
        $schema = new IntSchema($this->messageProvider);
        $result = $schema->safeParse('value');
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('the value must be integer');
    });

    it('should cast the value to an integer', function () {
        $schema = new IntSchema($this->messageProvider);
        $result = $schema->safeParse('123');
        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe(123);

        $result = $schema->safeParse(2.2);
        expect($result->succeed)->toBeFalse();
        expect($result->value)->toBe(2.2);

        $result = $schema->safeParse(null);
        expect($result->value)->toBeNull();

        $result = $schema->safeParse(new stdClass());
        expect($result->value)->toBeInstanceOf(stdClass::class);
    });
});
