<?php

namespace A7Pro\Account\Core\Application\Services\HeadOfDppList;

use A7Pro\Account\Core\Domain\Exceptions\UnauthorizedException;
use A7Pro\Account\Core\Domain\Exceptions\ValidationException;
use A7Pro\Account\Core\Domain\Models\User;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;

class HeadOfDppListService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(HeadOfDppListRequest $request)
    {
        // validate request
        $errors = $request->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // get authenticated user
        $authUser = $this->userRepository->getById($request->authUserId);

        if (!$authUser || !$authUser->hasRole(User::ROLE_SUPER_ADMIN)) {
            throw new UnauthorizedException();
        }

        // get head of dpp list
        $headOfDppList = $this->userRepository->getHeadOfDppList();

        return (new HeadOfDppListDto($headOfDppList))->data;
    }
}