<?php

namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Marketplace\Toko\Core\Domain\Exceptions\ValidationException;
use Exception;

class BaseController extends \A7Pro\Controllers\BaseController
{
    protected function handleException(Exception $e)
    {
        if ($e instanceof InvalidOperationException) {
            $this->sendError($e->getMessage(), $e->getCode());
        } else if ($e instanceof ValidationException) {
            $this->sendError($e->getMessage(), $e->getErrors(), $e->getCode());
        } else {
            $this->sendException($e);
        }
    }
}
