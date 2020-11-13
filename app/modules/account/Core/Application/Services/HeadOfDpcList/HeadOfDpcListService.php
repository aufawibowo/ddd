<?php

namespace A7Pro\Account\Core\Application\Services\HeadOfDpcList;

use A7Pro\Account\Core\Domain\Exceptions\UnauthorizedException;
use A7Pro\Account\Core\Domain\Exceptions\ValidationException;
use A7Pro\Account\Core\Domain\Models\User;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;

class HeadOfDpcListService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(HeadOfDpcListRequest $request)
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

        // get head of dpc list
        $headOfDpcList = $this->userRepository->getHeadOfDpcList();

        return (new HeadOfDpcListDto($headOfDpcList))->data;
    }
}