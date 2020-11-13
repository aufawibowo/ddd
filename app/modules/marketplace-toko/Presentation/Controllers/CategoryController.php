<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\ShowCategories\ShowCategoriesRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowCategories\ShowCategoriesService;

class CategoryController extends BaseController
{
    public function showCategoriesAction()
    {
        $sellerId = $this->getAuthUserId();

        $request = new ShowCategoriesRequest($sellerId);

        $service = new ShowCategoriesService(
            $this->di->get('categoryRepository')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
}
