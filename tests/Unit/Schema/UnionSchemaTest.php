<?php

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\ArraySchema;
use Rei\Phod\Schema\UnionSchema;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\Exception\PhodUnionParseFailedException;

describe('UnionSchema', function () {
    it('should return the value if it matches any schema', function () {
        $schema = new UnionSchema($this->messageProvider, [
            new StringSchema($this->messageProvider),
            new IntSchema($this->messageProvider),
        ]);

        expect($schema->parse('value'))->toBe('value');
        expect($schema->parse(123))->toBe('123');
    });

    it('should throw an exception if it does not match any schema', function () {
        $schema = new UnionSchema($this->messageProvider, [
            new ArraySchema($this->messageProvider, new StringSchema($this->messageProvider)),
            new IntSchema($this->messageProvider),
        ]);

        expect(fn() => $schema->parse(1.23))->toThrow(PhodUnionParseFailedException::class, 'Value must match one of the schemas');
    });

    it('should throw an exception has children errors if it does not match any schema', function () {
        $schema = new UnionSchema($this->messageProvider, [
            new ArraySchema($this->messageProvider, new StringSchema($this->messageProvider)),
            new IntSchema($this->messageProvider),
        ]);

        $result = $schema->safeParse(1.23);

        $exception = $result->exception;
        expect($exception)->toBeInstanceOf(PhodUnionParseFailedException::class);
        /** @var PhodUnionParseFailedException $exception */
        expect($exception->unionErrors)->toHaveLength(2);
        expect($exception->unionErrors[0]->issue->message)->toBe('the value must be array');
        expect($exception->unionErrors[1]->issue->message)->toBe('the value must be integer');
    });
});