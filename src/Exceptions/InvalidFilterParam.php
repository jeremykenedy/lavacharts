<?php

namespace Khill\Lavacharts\Exceptions;

class InvalidFilterParam extends LavaException
{
    public function __construct($badParam, $types)
    {
        $message = '(' . gettype($badParam) . ') is not a valid Filter parameter.';

        parent::__construct($message);
    }
}
