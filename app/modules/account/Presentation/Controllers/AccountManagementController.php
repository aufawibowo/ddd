<?php

namespace A7Pro\Account\Presentation\Controllers;

use A7Pro\Account\Core\Application\Services\ApproveTechnician\ApproveTechnicianRequest;
use A7Pro\Account\Core\Application\Services\ApproveTechnician\ApproveTechnicianService;
use A7Pro\Account\Core\Application\Services\HeadOfDpcList\HeadOfDpcListRequest;
use A7Pro\Account\Core\Application\Services\HeadOfDpcList\HeadOfDpcListService;
use A7Pro\Account\Core\Application\Services\HeadOfDppList\HeadOfDppListRequest;
use A7Pro\Account\Core\Application\Services\HeadOfDppList\HeadOfDppListService;
use A7Pro\Account\Core\Application\Services\RejectTechnician\RejectTechnicianRequest;
use A7Pro\Account\Core\Application\Services\RejectTechnician\RejectTechnicianService;
use A7Pro\Account\Core\Application\Services\SetHeadOfDpc\SetHeadOfDpcRequest;
use A7Pro\Account\Core\Application\Services\SetHeadOfDpc\SetHeadOfDpcService;
use A7Pro\Account\Core\Application\Services\SetHeadOfDpp\SetHeadOfDppRequest;
use A7Pro\Account\Core\Application\Services\SetHeadOfDpp\SetHeadOfDppService;
use A7Pro\Account\Core\Application\Services\UnverifiedTechnicianList\UnverifiedTechnicianListRequest;
use A7Pro\Account\Core\Application\Services\UnverifiedTechnicianList\UnverifiedTechnicianListService;

class AccountManagementController extends BaseController
{
    public function getHeadOfDpcListAction()
    {
        $authUserId = $this->getAuthUserId();

        $request = new HeadOfDpcListRequest($authUserId);
        $service = new HeadOfDpcListService($this->di->get('userRepository'));

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function setHeadOfDpcAction()
    {
        $authUserId = $this->getAuthUserId();
        $id = $this->request->getPost('id');

        $request = new SetHeadOfDpcRequest($authUserId, $id, false);
        $service = new SetHeadOfDpcService($this->di->get('userRepository'));

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function setHeadOfDpcSearchAction()
    {
        $authUserId = $this->getAuthUserId();
        $id = $this->request->getQuery('id');

        $request = new SetHeadOfDpcRequest($authUserId, $id, true);
        $service = new SetHeadOfDpcService($this->di->get('userRepository'));

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function getHeadOfDppListAction()
    {
        $authUserId = $this->getAuthUserId();

        $request = new HeadOfDppListRequest($authUserId);
        $service = new HeadOfDppListService($this->di->get('userRepository'));

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function setHeadOfDppAction()
    {
        $authUserId = $this->getAuthUserId();
        $id = $this->request->getPost('id');

        $request = new SetHeadOfDppRequest($authUserId, $id, false);
        $service = new SetHeadOfDppService($this->di->get('userRepository'));

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function setHeadOfDppSearchAction()
    {
        $authUserId = $this->getAuthUserId();
        $id = $this->request->getQuery('id');

        $request = new SetHeadOfDppRequest($authUserId, $id, true);
        $service = new SetHeadOfDppService($this->di->get('userRepository'));

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function getUnverifiedTechnicianListAction()
    {
        $userId = $this->getAuthUserId();

        $request = new UnverifiedTechnicianListRequest($userId);
        $service = new UnverifiedTechnicianListService($this->di->get('userRepository'));

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function approveTechnicianAction()
    {
        $userId = $this->getAuthUserId();
        $technicianId = $this->request->getPost('technician_id');

        $request = new ApproveTechnicianRequest($userId, $technicianId);
        $service = new ApproveTechnicianService($this->di->get('userRepository'));

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function rejectTechnicianAction()
    {
        $userId = $this->getAuthUserId();
        $technicianId = $this->request->getPost('technician_id');

        $request = new RejectTechnicianRequest($userId, $technicianId);
        $service = new RejectTechnicianService($this->di->get('userRepository'));

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}