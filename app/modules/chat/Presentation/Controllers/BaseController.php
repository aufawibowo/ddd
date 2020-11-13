<?php

namespace A7Pro\Chat\Presentation\Controllers;

use A7Pro\Chat\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Chat\Core\Domain\Exceptions\UnauthorizedException;
use A7Pro\Chat\Core\Domain\Exceptions\ValidationException;

class BaseController extends \A7Pro\Controllers\BaseController
{
    protected function handleException(\Exception $e)
    {
        if ($e instanceof InvalidOperationException) {
            $this->sendError($e->getMessage(), null, $e->getCode());
        } else if ($e instanceof ValidationException) {
            $this->sendError($e->getMessage(), $e->getErrors(), $e->getCode());
        } else if ($e instanceof UnauthorizedException) {
            $this->sendError($e->getMessage(), null, $e->getCode());
        } else {
            $this->sendException($e);
        }
    }
}
