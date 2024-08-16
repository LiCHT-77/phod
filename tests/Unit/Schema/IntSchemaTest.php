<?php

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\PhodParseFailedException;

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new IntSchema();
        $result = $schema->parse(123);
        expect($result)->toBe(123);
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new IntSchema();
        expect(fn() => $schema->parse('value'))->toThrow(PhodParseFailedException::class, 'Value must be an integer');
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new IntSchema();
        $result = $schema->safeParse(123);
        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not an integer', function () {
        $schema = new IntSchema();
        $result = $schema->safeParse('value');
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be an integer');
    });
});
