<?php

use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\PhodParseFailedException;

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new BoolSchema();
        $result = $schema->parse(true);

        expect($result)->toBeTrue();
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new BoolSchema();
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'Value must be a boolean');
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new BoolSchema();
        $result = $schema->safeParse(true);

        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not a boolean', function () {
        $schema = new BoolSchema();
        $result = $schema->safeParse('string');

        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be a boolean');
    });
});
