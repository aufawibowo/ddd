<?php

namespace A7Pro\Account\Core\Application\Services\GetUserById;

use A7Pro\Account\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Account\Core\Domain\Exceptions\ValidationException;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;

class GetUserByIdService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(GetUserByIdRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // get user
        $user = $this->userRepository->getById($request->userId);

        if (is_null($user)) {
            throw new InvalidOperationException('user_not_found');
        }

        return new GetUserByIdDto($user);
    }
}