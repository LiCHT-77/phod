<?php

use Rei\Phod\Schema\StringSchema;
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
            return strtr($message, array_map(fn($key) => ":$key", array_keys($params)));
        }
    };
});

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new StringSchema($this->messageProvider);
        $result = $schema->parse('value');

        expect($result)->toBe('value');
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new StringSchema($this->messageProvider);
        expect(fn() => $schema->parse(new stdClass()))->toThrow(PhodParseFailedException::class, 'Value must be a string');
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

        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not a string', function () {
        $schema = new StringSchema($this->messageProvider);
        $result = $schema->safeParse(new stdClass());

        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be a string');
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
