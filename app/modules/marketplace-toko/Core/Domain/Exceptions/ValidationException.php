<?php

namespace A7Pro\Marketplace\Toko\Core\Domain\Exceptions;

class ValidationException extends \Exception
{
    protected array $errors;

    public function __construct(array $errors, $message = 'Unprocessable Entity', $code = 422)
    {
        parent::__construct($message, $code);

        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
