<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\GetHomeData\GetHomeDataRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\GetHomeData\GetHomeDataService;

class HomeController extends BaseController
{
    public function getHomeDataAction()
    {
        $sellerId = $this->getAuthUserId();

        $request = new GetHomeDataRequest($sellerId);

        $service = new GetHomeDataService(
            $this->di->get('sellerRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
