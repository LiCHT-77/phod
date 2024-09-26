<?php

namespace Rei\Phod;

class PhodParseFailedException extends \Exception
{
    public function __construct(
        readonly public PhodParseIssue $issue,
    ) {
        parent::__construct($issue->message);
    }
}
