<?php

namespace Rei\Phod\Exception;

use Rei\Phod\PhodParseIssue;
use Rei\Phod\Exception\PhodParseFailedException;

class PhodUnionParseFailedException extends PhodParseFailedException
{
    public function __construct(
        readonly public array $unionErrors,
        PhodParseIssue $issue,

    ) {
        parent::__construct($issue);
    }

}