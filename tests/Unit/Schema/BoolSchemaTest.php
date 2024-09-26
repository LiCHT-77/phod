<?php

use Rei\Phod\PhodParseIssue;
use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\PhodParseFailedException;

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new BoolSchema($this->messageProvider);
        $result = $schema->parse(true);

        expect($result)->toBeTrue();
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new BoolSchema($this->messageProvider);
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'the value must be boolean');
    });

    it('should cast the value to a boolean', function () {
        $schema = new BoolSchema($this->messageProvider);
        expect($schema->parse(1))->toBeTrue();
        expect($schema->parse(0))->toBeFalse();
    });
});

describe('safeParse method', function () {
    it('should return a ParseResult object', function () {
        $schema = new BoolSchema($this->messageProvider);
        $result = $schema->safeParse(true);

        expect($result->success)->toBeTrue();
    });

    it('should return a ParseResult object with the correct message if the value is not a boolean', function () {
        $schema = new BoolSchema($this->messageProvider);
        $result = $schema->safeParse('string');

        expect($result->success)->toBeFalse();
        expect($result->exception)->toBeInstanceOf(PhodParseFailedException::class);
        expect($result->exception->issue)->toBeInstanceOf(PhodParseIssue::class);
        expect($result->exception->issue->message)->toBe('the value must be boolean');
    });

    it('should cast the value to a boolean', function () {
        $schema = new BoolSchema($this->messageProvider);
        $result = $schema->safeParse(1);
        expect($result->success)->toBeTrue();
        expect($result->value)->toBeTrue();

        $result = $schema->safeParse(0);
        expect($result->success)->toBeTrue();
        expect($result->value)->toBeFalse();
    });
});
