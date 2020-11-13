<?php

namespace A7Pro\Wallet\Core\Domain\Exceptions;

class ValidationException extends \Exception
{
    protected $errors;

    public function __construct(array $errors, $message = 'Unprocessable Entity', $code = 422) {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}