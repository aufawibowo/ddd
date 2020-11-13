<?php

namespace A7Pro\Account\Core\Application\Services\AdminLogin;

use A7Pro\Account\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Account\Core\Domain\Exceptions\UnauthorizedException;
use A7Pro\Account\Core\Domain\Exceptions\ValidationException;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;
use A7Pro\Account\Core\Domain\Services\TokenService;

class AdminLoginService
{
    private TokenService $tokenService;
    private UserRepository $userRepository;

    public function __construct(TokenService $tokenService, UserRepository $userRepository)
    {
        $this->tokenService = $tokenService;
        $this->userRepository = $userRepository;
    }

    public function execute(AdminLoginRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // get user
        $user = null;

        if (is_numeric($request->id)) {
            $user = $this->userRepository->getByPhone($request->id);
        } else if (filter_var($request->id, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userRepository->getByEmail($request->id);
        } else {
            $user = $this->userRepository->getByUsername($request->id);
        }

        if (is_null($user)) {
            throw new InvalidOperationException('user_not_found');
        }

        // check if user administrator
        if (!$user->isAdministrator()) {
            throw new InvalidOperationException('credentials_does_not_match');
        }

        // verify password
        if (!$user->verifyPassword($request->password)) {
            throw new InvalidOperationException('credentials_does_not_match');
        }

        // create token
        $token = $this->tokenService->encode($user->getId()->id());

        return new AdminLoginDto($token, 'Bearer', $user);
    }
}