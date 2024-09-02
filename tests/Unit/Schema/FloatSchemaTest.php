<?php

use Rei\Phod\Schema\FloatSchema;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\PhodParseFailedException;

beforeEach(function () {
    $this->messageProvider = new class implements MessageProvider {
        public function get(string $key): string
        {
            return 'Value must be a float';
        }

        public function message(string $key, array $params = []): string
        {
            return 'Value must be a float';
        }

        public function replace(string $message, array $params = []): string
        {
            return str_replace(array_keys($params), array_values($params), $message);
        }
    };
});

describe('parse method', function () {
    it('should return the value if all validators return true', function () {
        $schema = new FloatSchema($this->messageProvider);
        $result = $schema->parse(1.23);

        expect($result)->toBe(1.23);
    });

    it('should throw an exception if any validator returns false', function () {
        $schema = new FloatSchema($this->messageProvider);
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'Value must be a float');
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

        expect($result->succeed)->toBeTrue();
    });

    it('should return a ParseResult object with the correct message if the value is not a float', function () {
        $schema = new FloatSchema($this->messageProvider);
        $result = $schema->safeParse('string');

        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be a float');

        $result = $schema->safeParse(new stdClass());
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('Value must be a float');
    });

    it('should cast the value to a float', function () {
        $schema = new FloatSchema($this->messageProvider);
        $result = $schema->safeParse('1.23');

        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe(1.23);

        $result = $schema->safeParse(2);
        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe(2.0);

        $result = $schema->safeParse(null);
        expect($result->value)->toBeNull();

        $result = $schema->safeParse(new stdClass());
        expect($result->value)->toBeInstanceOf(stdClass::class);
    });
});
