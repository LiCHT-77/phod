<?php

use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\PhodParseFailedException;

beforeEach(function () {
    $this->messageProvider = new class implements MessageProvider {
        public function get(string $key): string
        {
            return 'Value must be a boolean';
        }

        public function message(string $key, array $params = []): string
        {
            return 'Value must be a boolean';
        }

        public function replace(string $message, array $params = []): string
        {
            return str_replace(array_keys($params), array_values($params), $message);
        }
    };
});

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new BoolSchema($this->messageProvider);
        $result = $schema->parse(true);

        expect($result)->toBeTrue();
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new BoolSchema($this->messageProvider);
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'Value must be a boolean');
    });
});

describe('safeParse method', function () {
    it('should return a ParseResult object', function () {
        $schema = new BoolSchema($this->messageProvider);
        $result = $schema->safeParse(true);

        expect($result->succeed)->toBeTrue();
    });

    it('should return a ParseResult object with the correct message if the value is not a boolean', function () {
        $schema = new BoolSchema($this->messageProvider);
        $result = $schema->safeParse('string');

        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be a boolean');
    });
});
