<?php

use Rei\Phod\PhodParseFailedException;
use Rei\Phod\PhodSchema;

describe('refine method', function () {
    it('should return a new schema with the given callable', function () {
        $schema = new PhodSchema();
        $newSchema = $schema->refine(function ($value, $failed) {
            return $value;
        });

        expect($newSchema)
            ->toBeInstanceOf(PhodSchema::class)
            ->not
            ->toBe($schema);
    });

    it('should merge the new callable with the existing ones', function () {
        $schema = new PhodSchema([
            fn($value, $failed) => is_string($value) ?: $failed('Value must be a string'),
        ]);

        $newSchema = $schema->refine(fn($value, $failed) => strlen($value) >= 5 ?: $failed('String must be at least 5 characters long'));

        expect($newSchema->parse('value'))->toBe('value');
        expect(fn() => $newSchema->parse('123'))->toThrow(PhodParseFailedException::class, 'String must be at least 5 characters long');
    });
});

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new PhodSchema([
            fn($value, $failed) => is_string($value) ?: $failed('Value must be a string'),
            fn($value, $failed) => strlen($value) >= 5 ?: $failed('String must be at least 5 characters long'),
        ]);

        expect($schema->parse('value'))->toBe('value');
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new PhodSchema([
            fn($value, $failed) => is_string($value) ?: $failed('Value must be a string'),
            fn($value, $failed) => strlen($value) >= 5 ?: $failed('String must be at least 5 characters long'),
        ]);

        expect(fn() => $schema->parse('123'))->toThrow(PhodParseFailedException::class, 'String must be at least 5 characters long');
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new PhodSchema([
            fn($value, $failed) => is_string($value) ?: $failed('Value must be a string'),
            fn($value, $failed) => strlen($value) >= 5 ?: $failed('String must be at least 5 characters long'),
        ]);

        $result = $schema->safeParse('value');

        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe('value');
    });

    it('should return a failed ParseResult object if any validator returns false', function () {
        $schema = new PhodSchema([
            fn($value, $failed) => is_string($value) ?: $failed('Value must be a string'),
            fn($value, $failed) => strlen($value) >= 5 ?: $failed('String must be at least 5 characters long'),
        ]);

        $result = $schema->safeParse('123');

        expect($result->succeed)->toBeFalse();
        expect($result->value)->toBe('123');
        expect($result->message)->toBe('String must be at least 5 characters long');
    });
});
