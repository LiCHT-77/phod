<?php

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\ArraySchema;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\PhodParseFailedException;

beforeEach(function () {
    $this->messageProvider = new class implements MessageProvider {
        public function get(string $key): string
        {
            return 'Value must be an array';
        }

        public function message(string $key, array $params = []): string
        {
            return 'Value must be an array';
        }

        public function replace(string $message, array $params = []): string
        {
            return str_replace(array_keys($params), array_values($params), $message);
        }
    };
});

describe('parse method', function () {
    it('should throw an exception if any validator returns false', function () {
        $schema = new ArraySchema($this->messageProvider, []);
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'Value must be an array');
    });

    it('should return the value if all validators return true', function () {
        $schema = new ArraySchema($this->messageProvider, [
            'name' => new StringSchema($this->messageProvider),
            'age' => new IntSchema($this->messageProvider),
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
        $schema = new ArraySchema($this->messageProvider, []);
        $result = $schema->safeParse([]);
        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not an array', function () {
        $schema = new ArraySchema($this->messageProvider, []);
        $result = $schema->safeParse('string');
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be an array');
    });
});
