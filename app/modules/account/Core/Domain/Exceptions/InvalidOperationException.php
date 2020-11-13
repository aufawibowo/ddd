<?php

namespace A7Pro\Account\Core\Domain\Exceptions;

class InvalidOperationException extends \Exception
{
    public function __construct($message, $code = 400) {
        parent::__construct($message, $code);
    }
}