<?php


namespace A7Pro\Marketplace\Customer\Presentation\Controllers;


use A7Pro\Marketplace\Customer\Core\Application\Services\Product\SearchProduct\SearchProductRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Product\SearchProduct\SearchProductService;
use A7Pro\Marketplace\Customer\Core\Application\Services\Product\SeeProductsDetailed\ShowProductsDetailedRequest;
use A7Pro\Marketplace\Customer\Core\Application\Services\Product\SeeProductsDetailed\ShowProductsDetailedService;
use Exception as ExceptionAlias;

class ProductController extends BaseController
{
    public function getProductRecommendationAction()
    {
        return "getProductRecommendationController";
    }

    public function searchProductAction()
    {
        $keyword = $this->request->get('keyword');
        $page = $this->request->get('page');
        $limit = $this->request->get('limit');
        $order = $this->request->get('order');
        $sortKey = $this->request->get('sort_key');
        $min_price = $this->request->get('min_price');
        $max_price = $this->request->get('max_price');
        $product_location = $this->request->get('product_location');

        $request = new SearchProductRequest(
            $keyword,
            $page,
            $limit,
            $sortKey,
            $order,
            $min_price,
            $max_price,
            $product_location
        );

        $service = new SearchProductService(
            $this->di->get('productRepository'),
            $this->di->get('productPhotosService')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (ExceptionAlias $e) {
            $this->handleException($e);
        }
    }

    public function getProductDetailAction()
    {
        $productId = $this->dispatcher->getParam('productId');
        $request = new ShowProductsDetailedRequest($productId);

        $service = new ShowProductsDetailedService(
            $this->di->get('productRepository'),
            $this->di->get('productPhotosService')
        );

        try {
            $result = $service->execute($request);

            $this->sendData($result);
        } catch (ExceptionAlias $e) {
            $this->handleException($e);
        }
    }

}