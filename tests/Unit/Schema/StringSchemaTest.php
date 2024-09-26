<?php

use Rei\Phod\PhodParseIssue;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\PhodParseFailedException;


describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new StringSchema($this->messageProvider);
        $result = $schema->parse('value');

        expect($result)->toBe('value');
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new StringSchema($this->messageProvider);
        expect(fn() => $schema->parse(new stdClass()))->toThrow(PhodParseFailedException::class, 'the value must be string');
    });

    it('should cast the value to a string', function () {
        $schema = new StringSchema($this->messageProvider);
        expect($schema->parse(123))->toBe('123');
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new StringSchema($this->messageProvider);
        $result = $schema->safeParse('value');

        expect($result->success)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not a string', function () {
        $schema = new StringSchema($this->messageProvider);
        $result = $schema->safeParse(new stdClass());

        expect($result->success)->toBeFalse();
        expect($result->exception)->toBeInstanceOf(PhodParseFailedException::class);
        expect($result->exception->issue)->toBeInstanceOf(PhodParseIssue::class);
        expect($result->exception->issue->message)->toBe('the value must be string');
    });

    it('should cast the value to a string', function () {
        $schema = new StringSchema($this->messageProvider);
        $result = $schema->safeParse(123);
        expect($result->value)->toBe('123');

        $result = $schema->safeParse(null);
        expect($result->value)->toBeNull();

        $result = $schema->safeParse(new stdClass());
        expect($result->value)->toBeInstanceOf(stdClass::class);
    });
});
