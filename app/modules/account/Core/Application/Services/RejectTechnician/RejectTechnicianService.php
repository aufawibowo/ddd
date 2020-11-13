<?php

namespace A7Pro\Account\Core\Application\Services\RejectTechnician;

use A7Pro\Account\Core\Domain\Exceptions\InvalidOperationException;
use A7Pro\Account\Core\Domain\Exceptions\UnauthorizedException;
use A7Pro\Account\Core\Domain\Exceptions\ValidationException;
use A7Pro\Account\Core\Domain\Repositories\UserRepository;

class RejectTechnicianService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(RejectTechnicianRequest $request)
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

        // get technician
        $technician = $this->userRepository->getById($request->technicianId);

        if (is_null($technician)) {
            throw new InvalidOperationException('technician_not_found');
        }

        // cek authorization
        $userDpc = $user->getTechnicianAttributes()->getDpc();
        $technicianDpc = $technician->getTechnicianAttributes()->getDpc();

        if (!$user->canVerifyTechnician() || !$userDpc->equals($technicianDpc)) {
            throw new UnauthorizedException();
        }

        // reject technician
        $technician->rejectAsTechnician();

        // validate changes
        $errors = $technician->validate();

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        // persist changes
        $this->userRepository->update($technician);
    }
}