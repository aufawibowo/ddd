<?php

namespace A7Pro\Account\Core\Domain\Exceptions;

class UnauthorizedException extends \Exception
{
    public function __construct($message = 'Unauthorized', $code = 403) {
        parent::__construct($message, $code);
    }
}