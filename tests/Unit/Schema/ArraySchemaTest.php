<?php

use Rei\Phod\Schema\ArraySchema;
use Rei\Phod\PhodParseFailedException;

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new ArraySchema();
        $result = $schema->parse([]);
        expect($result)->toBe([]);
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new ArraySchema();
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'Value must be an array');
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new ArraySchema();
        $result = $schema->safeParse([]);
        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not an array', function () {
        $schema = new ArraySchema();
        $result = $schema->safeParse('string');
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be an array');
    });
});
