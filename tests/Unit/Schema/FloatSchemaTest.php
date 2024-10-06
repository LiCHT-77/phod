<?php

use Rei\Phod\PhodParseIssue;
use Rei\Phod\Schema\FloatSchema;
use Rei\Phod\Exception\PhodParseFailedException;


describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new FloatSchema($this->messageProvider);
        $result = $schema->parse(1.23);

        expect($result)->toBe(1.23);
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new FloatSchema($this->messageProvider);
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'the value must be float');
    });

    it('should cast the value to a float', function () {
        $schema = new FloatSchema($this->messageProvider);
        $result = $schema->parse('1.23');

        expect($result)->toBe(1.23);
    });
});

describe('safeParse method', function () {
    it('should return a ParseResult object', function () {
        $schema = new FloatSchema($this->messageProvider);
        $result = $schema->safeParse(1.23);

        expect($result->success)->toBeTrue();
    });

    it('should return a ParseResult object with the correct message if the value is not a float', function () {
        $schema = new FloatSchema($this->messageProvider);
        $result = $schema->safeParse('string');

        expect($result->success)->toBeFalse();
        expect($result->exception)->toBeInstanceOf(PhodParseFailedException::class);
        expect($result->exception->issue)->toBeInstanceOf(PhodParseIssue::class);
        expect($result->exception->issue->message)->toBe('the value must be float');

        $result = $schema->safeParse(new stdClass());
        expect($result->success)->toBeFalse();
        expect($result->exception)->toBeInstanceOf(PhodParseFailedException::class);
        expect($result->exception->issue)->toBeInstanceOf(PhodParseIssue::class);
        expect($result->exception->issue->message)->toBe('the value must be float');
    });

    it('should cast the value to a float', function () {
        $schema = new FloatSchema($this->messageProvider);
        $result = $schema->safeParse('1.23');

        expect($result->success)->toBeTrue();
        expect($result->value)->toBe(1.23);

        $result = $schema->safeParse(2);
        expect($result->success)->toBeTrue();
        expect($result->value)->toBe(2.0);

        $result = $schema->safeParse(null);
        expect($result->value)->toBeNull();

        $result = $schema->safeParse(new stdClass());
        expect($result->value)->toBeInstanceOf(stdClass::class);
    });
});
