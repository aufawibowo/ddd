<?php

namespace A7Pro\Controllers;

use Phalcon\Mvc\Controller;

class BaseController extends Controller
{
    protected function getAuthUserId()
    {
        $tokenService = $this->di->get('tokenService');
        $authHeader = $this->request->getHeader('Authorization');

        $token = null;
        if (!empty($authHeader)) {
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $token = $matches[1];
            }
        }

        try {
            $decoded = $tokenService->decode($token);

            return $decoded['sub'];
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function sendOk($message = 'OK')
    {
        $this->response->setStatusCode(200)
            ->setJsonContent([
                'success' => true,
                'message' => $message
            ]);
    }

    protected function sendData($data)
    {
        $this->response->setStatusCode(200)
            ->setJsonContent([
                'success' => true,
                'data' => $data
            ]);
    }

    protected function sendError($message, $errors = [], $code = 400)
    {
        $this->response->setStatusCode($code)
            ->setJsonContent([
                'success' => false,
                'message' => $message,
                'data' => $errors
            ]);
    }

    protected function sendException(\Exception $e)
    {
        $this->response->setStatusCode(500, 'Internal Server Error')
            ->setJsonContent([
                'success' => false,
                // 'message' => 'Unknown error, please try again later.'
                'message' => $e->getMessage()
            ]);
    }
}
