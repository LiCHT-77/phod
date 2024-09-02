<?php

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\PhodParseFailedException;

beforeEach(function () {
    $this->messageProvider = new class implements MessageProvider {
        public function get(string $key): string
        {
            return 'Value must be an integer';
        }

        public function message(string $key, array $params = []): string
        {
            return 'Value must be an integer';
        }

        public function replace(string $message, array $params = []): string
        {
            return str_replace(array_keys($params), array_values($params), $message);
        }
    };
});

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new IntSchema($this->messageProvider);
        $result = $schema->parse(123);
        expect($result)->toBe(123);
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new IntSchema($this->messageProvider);
        expect(fn() => $schema->parse('value'))->toThrow(PhodParseFailedException::class, 'Value must be an integer');
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new IntSchema($this->messageProvider);
        $result = $schema->safeParse(123);
        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not an integer', function () {
        $schema = new IntSchema($this->messageProvider);
        $result = $schema->safeParse('value');
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be an integer');
    });
});
