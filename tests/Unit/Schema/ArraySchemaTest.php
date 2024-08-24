<?php

use Rei\Phod\Schema\ArraySchema;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\Schema\IntSchema;
use Rei\Phod\PhodParseFailedException;

describe('parse method', function () {
    it('should throw an exception if any validator returns false', function () {
        $schema = new ArraySchema([]);
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'Value must be an array');
    });

    it('should return the value if all validators return true', function () {
        $schema = new ArraySchema([
            'name' => new StringSchema(),
            'age' => new IntSchema(),
        ]);
        $result = $schema->parse([
            'name' => 'John',
            'age' => 30,
        ]);
        expect($result)->toBe([
            'name' => 'John',
            'age' => 30,
        ]);
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new ArraySchema([]);
        $result = $schema->safeParse([]);
        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not an array', function () {
        $schema = new ArraySchema([]);
        $result = $schema->safeParse('string');
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be an array');
    });
});
