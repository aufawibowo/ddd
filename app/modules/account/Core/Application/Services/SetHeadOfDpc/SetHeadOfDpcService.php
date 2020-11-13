<?php

namespace A7Pro\Account\Core\Application\Services\SetHeadOfDpc;

use A7Pro\Account\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Account\Core\Domain\Exceptions\UnauthorizedException;
use A7Pro\Account\Core\Domain\Exceptions\ValidationException;
use A7Pro\Account\Core\Domain\Models\User;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;

class SetHeadOfDpcService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(SetHeadOfDpcRequest $request)
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

        // get user to be head of dpc
        $user = null;

        if (is_numeric($request->id)) {
            $user = $this->userRepository->getByPhone($request->id);
        } else if (filter_var($request->id, FILTER_VALIDATE_EMAIL)) {
            $user = $this->userRepository->getByEmail($request->id);
        }

        if (is_null($user)) {
            throw new InvalidOperationException('user_not_found');
        }

        if ($request->isSearch) {
            return new SetHeadOfDpcDto($user);
        }

        // give head of dpc role
        $user->giveHeadOfDpcRole();

        // validate changes
        $errors = $user->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // persist changes
        return $this->userRepository->update($user);
    }
}