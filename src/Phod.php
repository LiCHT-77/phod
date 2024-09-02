<?php

namespace Rei\Phod;

use Rei\Phod\Schema\IntSchema;
use Rei\Phod\Schema\BoolSchema;
use Rei\Phod\Schema\ArraySchema;
use Rei\Phod\Schema\FloatSchema;
use Rei\Phod\Schema\StringSchema;
use Rei\Phod\Message\MessageProvider;


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
     * @param array{invalid_type_message?: string, required_message?: string} $options
     * @return StringSchema
     */
    public function string(array $options = []): StringSchema
    {
        return new StringSchema($this->messageProvider, $options);
    }

    /**
     * Make an IntSchema.
     *
     * @param array{invalid_type_message?: string, required_message?: string} $options
     * @return IntSchema
     */
    public function int(array $options = []): IntSchema
    {
        return new IntSchema($this->messageProvider, $options);
    }

    /**
     * Make a FloatSchema.
     *
     * @param array{invalid_type_message?: string, required_message?: string} $options
     * @return FloatSchema
     */
    public function float(array $options = []): FloatSchema
    {
        return new FloatSchema($this->messageProvider, $options);
    }

    /**
     * Make a BoolSchema.
     *
     * @param array{invalid_type_message?: string, required_message?: string} $options
     * @return BoolSchema
     */
    public function bool(array $options = []): BoolSchema
    {
        return new BoolSchema($this->messageProvider, $options);
    }

    /**
     * Make an ArraySchema.
     *
     * @param array<int, mixed> $data
     * @param array{invalid_type_message?: string, required_message?: string} $options
     * @return ArraySchema
     */
    public function array(array $data = [], array $options = []): ArraySchema
    {
        return new ArraySchema($this->messageProvider, $data, $options);
    }
}
