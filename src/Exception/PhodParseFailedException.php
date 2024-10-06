<?php

namespace Rei\Phod\Exception;

use Rei\Phod\PhodParseIssue;

class PhodParseFailedException extends \Exception
{
    public function __construct(
        readonly public PhodParseIssue $issue,
    ) {
        parent::__construct($issue->message);
    }
}
