<?php

use Rei\Phod\PhodSchema;
use Rei\Phod\ParseResult;
use Rei\Phod\ParseContext;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\PhodParseFailedException;

beforeEach(function () {
    $this->messageProvider = new class implements MessageProvider {
        public function get(string $key): string
        {
            return 'Value must be a string';
        }

        public function message(string $key, array $params = []): string
        {
            return 'Value must be a string';
        }

        public function replace(string $message, array $params = []): string
        {
            return str_replace(array_keys($params), array_values($params), $message);
        }
    };
});

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new PhodSchema($this->messageProvider, [
            function (mixed $value, ParseContext $context): ParseResult {
                if (is_string($value)) {
                    return new ParseResult(true, $value);
                }
                return new ParseResult(false, $value, 'Value must be a string');
            },
            function (mixed $value, ParseContext $context): ParseResult {
                if (strlen($value) >= 5) {
                    return new ParseResult(true, $value);
                }
                return new ParseResult(false, $value, 'String must be at least 5 characters long');
            },
        ]);

        expect($schema->parse('value'))->toBe('value');
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new PhodSchema($this->messageProvider, [
            function (mixed $value, ParseContext $context): ParseResult {
                if (is_string($value)) {
                    return new ParseResult(true, $value);
                }
                return new ParseResult(false, $value, 'Value must be a string');
            },
            function (mixed $value, ParseContext $context): ParseResult {
                if (strlen($value) >= 5) {
                    return new ParseResult(true, $value);
                }
                return new ParseResult(false, $value, 'String must be at least 5 characters long');
            },
        ]);

        expect(fn() => $schema->parse('123'))->toThrow(PhodParseFailedException::class, 'String must be at least 5 characters long');
    });
});

describe('safeParse method', function () {
    it('should return a ParseResult object', function () {
        $schema = new PhodSchema($this->messageProvider, [
            function (mixed $value, ParseContext $context): ParseResult {
                if (is_string($value)) {
                    return new ParseResult(true, $value);
                }
                return new ParseResult(false, $value, 'Value must be a string');
            },
            function (mixed $value, ParseContext $context): ParseResult {
                if (strlen($value) >= 5) {
                    return new ParseResult(true, $value);
                }
                return new ParseResult(false, $value, 'String must be at least 5 characters long');
            },
        ]);

        $result = $schema->safeParse('value');

        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe('value');
    });

    it('should return a failed ParseResult object if any validator returns false', function () {
        $schema = new PhodSchema($this->messageProvider, [
            function (mixed $value, ParseContext $context): ParseResult {
                if (is_string($value)) {
                    return new ParseResult(true, $value);
                }
                return new ParseResult(false, $value, 'Value must be a string');
            },
            function (mixed $value, ParseContext $context): ParseResult {
                if (strlen($value) >= 5) {
                    return new ParseResult(true, $value);
                }
                return new ParseResult(false, $value, 'String must be at least 5 characters long');
            },
        ]);

        $result = $schema->safeParse('123');

        expect($result->succeed)->toBeFalse();
        expect($result->value)->toBe('123');
        expect($result->message)->toBe('String must be at least 5 characters long');
    });
});
