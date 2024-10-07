<?php

use DateTimeImmutable;
use Rei\Phod\Schema\DateTimeSchema;
use Rei\Phod\Exception\PhodParseFailedException;

describe('safeParse method', function () {
    it('should parse a valid date', function () {
        $schema = new DateTimeSchema(
            $this->messageProvider,
        );

        $result = $schema->safeParse(new DateTimeImmutable('2024-01-01'));
        expect($result->success)->toBeTrue();
        expect($result->value)->toBeInstanceOf(DateTimeImmutable::class);
    });

    it('should throw an error if the date is invalid type', function () {
        $schema = new DateTimeSchema(
            $this->messageProvider,
        );
        $result = $schema->safeParse('invalid date');
        expect($result->success)->toBeFalse();
        $exception = $result->exception;
        expect($exception)->toBeInstanceOf(PhodParseFailedException::class);
        /** @var PhodParseFailedException $exception */
        expect($exception->issue->message)->toBe('the value must be DateTimeInterface');
    });
});
