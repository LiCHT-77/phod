<?php

namespace Rei\Phod;

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\Schema\FloatSchema;
use Rei\Phod\Schema\UnionSchema;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\Schema\LiteralSchema;
use Rei\Phod\Schema\DateTimeSchema;
use Rei\Phod\Message\MessageProvider;
use Rei\Phod\Schema\AssociativeArraySchema;

class Phod
{
    /**
     * @param MessageProvider $messageProvider
     */
    public function __construct(protected MessageProvider $messageProvider) {
        //
    }

    /**
     * Make a StringSchema.
     *
     * @param array{invalid_type_message?: string} $options
     * @return StringSchema
     */
    public function string(array $options = []): StringSchema
    {
        return new StringSchema($this->messageProvider, $options);
    }

    /**
     * Make an IntSchema.
     *
     * @param array{invalid_type_message?: string} $options
     * @return IntSchema
     */
    public function int(array $options = []): IntSchema
    {
        return new IntSchema($this->messageProvider, $options);
    }

    /**
     * Make a FloatSchema.
     *
     * @param array{invalid_type_message?: string} $options
     * @return FloatSchema
     */
    public function float(array $options = []): FloatSchema
    {
        return new FloatSchema($this->messageProvider, $options);
    }

    /**
     * Make a BoolSchema.
     *
     * @param array{invalid_type_message?: string} $options
     * @return BoolSchema
     */
    public function bool(array $options = []): BoolSchema
    {
        return new BoolSchema($this->messageProvider, $options);
    }

    /**
     * Make an AssociativeArraySchema.
     *
     * @param array<string, PhodSchema> $data
     * @param array{invalid_type_message?: string, required_message?: string} $options
     * @return AssociativeArraySchema
     */
    public function array(array $data = [], array $options = []): AssociativeArraySchema
    {
        return new AssociativeArraySchema($this->messageProvider, $data, $options);
    }

    /**
     * Make a UnionSchema.
     *
     * @param PhodSchema[] $schemas
     * @return UnionSchema
     */
    public function union(array $schemas): UnionSchema
    {
        return new UnionSchema($this->messageProvider, $schemas);
    }

    /**
     * Make a LiteralSchema.
     *
     * @param mixed $value
     * @param array{invalid_literal_message?: string} $options
     * @return LiteralSchema
     */
    public function literal(mixed $value, array $options = []): LiteralSchema
    {
        return new LiteralSchema($this->messageProvider, $value, $options);
    }

    /**
     * Make a DateTimeSchema.
     *
     * @param array{invalid_type_message?: string} $options
     * @return DateTimeSchema
     */
    public function dateTime(array $options = []): DateTimeSchema
    {
        return new DateTimeSchema($this->messageProvider, $options);
    }
}
