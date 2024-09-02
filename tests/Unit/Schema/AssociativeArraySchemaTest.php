<?php

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\AssociativeArraySchema;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\PhodParseFailedException;

describe('parse method', function () {
    it('should throw an exception if any validator returns false', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, []);
        expect(fn() => $schema->parse('string'))->toThrow(PhodParseFailedException::class, 'the value must be array');
    });

    it('should return the value if all validators return true', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, [
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

    it('should cast the value to an array', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, [
            'name' => new StringSchema($this->messageProvider),
            'age' => new IntSchema($this->messageProvider),
        ]);

        $data = new stdClass();
        $data->name = 'John';
        $data->age = 30;

        $result = $schema->parse($data);
        expect($result)->toBe([
            'name' => 'John',
            'age' => 30,
        ]);
    });
});

describe('safeParse method', function () {
    it('should return a PaseResult object', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, []);
        $result = $schema->safeParse([]);
        expect($result->succeed)->toBeTrue();
    });

    it('should return a PaseResult object with the correct message if the value is not an array', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, []);
        $result = $schema->safeParse('string');
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('the value must be array');

        $result = $schema->safeParse(null);
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('the value must be array');
    });

    it('should cast the value to an array', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, [
            'name' => new StringSchema($this->messageProvider),
            'age' => new IntSchema($this->messageProvider),
        ]);

        $data = new stdClass();
        $data->name = 'John';
        $data->age = 30;

        $result = $schema->safeParse($data);
        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe([
            'name' => 'John',
            'age' => 30,
        ]);
    });

    it('should cast elements of the array', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, [
            'name' => new StringSchema($this->messageProvider),
            'age' => new IntSchema($this->messageProvider),
        ]);

        $result = $schema->safeParse([
            'name' => 'John',
            'age' => '30',
        ]);
        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe([
            'name' => 'John',
            'age' => 30,
        ]);
    });
});

describe('optional method', function () {
    it('should set the value to be optional', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, []);
        $schema->optional();
        expect($schema->isOptional())->toBeTrue();
    });

    it('should return the value if the value is optional and the value is not set', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, [
            'name' => (new StringSchema($this->messageProvider))->optional(),
        ]);
        $result = $schema->safeParse([]);
        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe([]);
    });

    it('should return a ParseResult object with the correct message if the value does not has required keys', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, [
            'name' => (new StringSchema($this->messageProvider)),
        ]);
        $result = $schema->safeParse([
            'age' => 30,
        ]);
        expect($result->succeed)->toBeFalse();
        expect($result->message)->toBe('the name is required');
    });
});

describe('nullable method', function () {
    it('should return a ParseResult object if the value is null', function () {
        $schema = new AssociativeArraySchema($this->messageProvider, [
            'name' => (new StringSchema($this->messageProvider))->nullable(),
        ]);
        $result = $schema->safeParse([
            'name' => null,
        ]);
        expect($result->succeed)->toBeTrue();
        expect($result->value)->toBe([
            'name' => null,
        ]);
    });
});
