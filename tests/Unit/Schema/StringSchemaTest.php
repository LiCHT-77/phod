<?php

use Rei\Phod\Schema\StringSchema;
use Rei\Phod\PhodParseFailedException;

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new StringSchema();
        $result = $schema->parse('value');

        expect($result)->toBe('value');
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new StringSchema();
        expect(fn() => $schema->parse(123))->toThrow(PhodParseFailedException::class, 'Value must be a string');
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new StringSchema();
        $result = $schema->safeParse('value');

        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not a string', function () {
        $schema = new StringSchema();
        $result = $schema->safeParse(123);

        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be a string');
    });
});
