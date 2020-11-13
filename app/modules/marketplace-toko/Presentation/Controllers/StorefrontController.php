<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\CreateStorefront\CreateStorefrontRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\CreateStorefront\CreateStorefrontService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowStorefronts\ShowStorefrontsRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowStorefronts\ShowStorefrontsService;
use A7Pro\Marketplace\Toko\Core\Application\Services\DeleteStorefront\DeleteStorefrontRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\DeleteStorefront\DeleteStorefrontService;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateStorefront\UpdateStorefrontRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateStorefront\UpdateStorefrontService;

class StorefrontController extends BaseController
{
    public function createStorefrontAction()
    {
        $sellerId = $this->getAuthUserId();
        $name = $this->request->get('name');

        $request = new CreateStorefrontRequest($sellerId, $name);

        $service = new CreateStorefrontService(
            $this->di->get('storefrontRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function showStorefrontsAction()
    {
        $sellerId = $this->getAuthUserId();

        $request = new ShowStorefrontsRequest($sellerId);

        $service = new ShowStorefrontsService(
            $this->di->get('storefrontRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function deleteStorefrontAction()
    {
        $sellerId = $this->getAuthUserId();
        $storefrontId = $this->dispatcher->getParam('storefront_id');

        $request = new DeleteStorefrontRequest($storefrontId, $sellerId);

        $service = new DeleteStorefrontService(
            $this->di->get('storefrontRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function updateStorefrontAction()
    {
        $sellerId = $this->getAuthUserId();
        $storefrontId = $this->dispatcher->getParam('storefront_id');
        $name = $this->request->get('name');

        $request = new UpdateStorefrontRequest($name, $sellerId, $storefrontId);

        $service = new UpdateStorefrontService(
            $this->di->get('storefrontRepository')
        );

        try {
            $service->execute($request);

            $this->sendOk();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
