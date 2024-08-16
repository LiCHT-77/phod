<?php

use Rei\Phod\Schema\FloatSchema;
use Rei\Phod\PhodParseFailedException;

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new FloatSchema();
        $result = $schema->parse(1.23);

        expect($result)->toBe(1.23);
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new FloatSchema();
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'Value must be a float');
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new FloatSchema();
        $result = $schema->safeParse(1.23);

        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not a float', function () {
        $schema = new FloatSchema();
        $result = $schema->safeParse('string');

        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be a float');

        $result = $schema->safeParse(123);
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be a float');
    });
});
