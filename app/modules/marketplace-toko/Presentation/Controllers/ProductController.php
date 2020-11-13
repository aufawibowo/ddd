<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateIsActiveProducts\UpdateIsActiveProductsRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateIsActiveProducts\UpdateIsActiveProductsService;
use A7Pro\Marketplace\Toko\Core\Application\Services\CreateProduct\CreateProductRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\CreateProduct\CreateProductService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowProducts\ShowProductsRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowProducts\ShowProductsService;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleProduct\ShowSingleProductRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\ShowSingleProduct\ShowSingleProductService;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateProduct\UpdateProductRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateProduct\UpdateProductService;
use A7Pro\Marketplace\Toko\Core\Application\Services\DeleteProducts\DeleteProductsRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\DeleteProducts\DeleteProductsService;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateProductsStorefront\UpdateProductsStorefrontRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\UpdateProductsStorefront\UpdateProductsStorefrontService;

class ProductController extends BaseController
{
	public function createProductAction()
	{
		$sellerId = $this->getAuthUserId();
		$productName = $this->request->get('product_name');
		$category = $this->request->get('category');
		$description = $this->request->get('description');
		$stock = $this->request->get('stock');
		$price = $this->request->get('price');
		$minOrder = $this->request->get('min_order') ?: 1;
		$weight = $this->request->get('weight');
		$condition = (int) $this->request->get('condition') ? 1 : 0;
		$isActive = (int) $this->request->get('is_active') ? 1 : 0;
		$storefrontId = $this->request->get('storefront_id');
		$photos = $this->request->getUploadedFiles();
		$brand = $this->request->get('brand');
		$warranty = $this->request->get('warranty');
		$warrantyPeriod = $this->request->get('warranty_period');

		$request = new CreateProductRequest(
			$sellerId,
			$productName,
			$category,
			$description,
			$stock,
			$price,
			$minOrder,
			$weight,
			$condition,
			$isActive,
			$storefrontId,
			$photos,
			$warranty,
			$warrantyPeriod,
			$brand
		);

		$service = new CreateProductService(
			$this->di->get('productRepository'),
			$this->di->get('productPhotosService'),
			$this->di->get('productPhotosRepository')
		);

		try {
			$service->execute($request);

			$this->sendOk();
		} catch (\Exception $e) {
			$this->handleException($e);
		}
	}

	public function showProductsAction()
	{
		$sellerId = $this->getAuthUserId();
		$page = $this->request->get('page');
		$limit = $this->request->get('limit');
		$filters = $this->request->get('filters');

		$request = new ShowProductsRequest($sellerId, $page, $limit, $filters);

		$service = new ShowProductsService(
			$this->di->get('productRepository'),
			$this->di->get('productPhotosService')
		);

		try {
			$result = $service->execute($request);

			$this->sendData($result);
		} catch (\Exception $e) {
			$this->handleException($e);
		}
	}

	public function getSingleProductAction()
	{
		$sellerId = $this->getAuthUserId();
		$productId = $this->dispatcher->getParam('product_id');

		$request = new ShowSingleProductRequest($sellerId, $productId);

		$service = new ShowSingleProductService(
			$this->di->get('productRepository'),
			$this->di->get('productPhotosService')
		);

		try {
			$result = $service->execute($request);

			$this->sendData($result);
		} catch (\Exception $e) {
			$this->handleException($e);
		}
	}

	public function updateProductAction()
	{
		$sellerId = $this->getAuthUserId();
		$productId = $this->dispatcher->getParam('product_id');
		$productName = $this->request->get('product_name');
		$description = $this->request->get('description');
		$category = $this->request->get('category');
		$stock = (int) $this->request->get('stock');
		$price = (int) $this->request->get('price');
		$minOrder = (int) $this->request->get('min_order') ?: 1;
		$weight = (int) $this->request->get('weight');
		$condition = (int) $this->request->get('condition') ? 1 : 0;
		$isActive = (int) $this->request->get('is_active') ? 1 : 0;
		$storefrontId = $this->request->get('storefront_id');
		$photos = $this->request->getUploadedFiles();
		$brand = $this->request->get('brand');
		$warranty = $this->request->get('warranty');
		$warrantyPeriod = $this->request->get('warranty_period');

		$request = new UpdateProductRequest(
			$productId,
			$sellerId,
			$productName,
			$category,
			$description,
			$stock,
			$price,
			$minOrder,
			$weight,
			$condition,
			$isActive,
			$storefrontId,
			$photos,
			$warranty,
			$warrantyPeriod,
			$brand
		);

		$service = new UpdateProductService(
			$this->di->get('productRepository'),
			$this->di->get('productPhotosService'),
			$this->di->get('productPhotosRepository')
		);

		try {
			$service->execute($request);

			$this->sendOk();
		} catch (\Exception $e) {
			$this->handleException($e);
		}
	}

	public function deleteProductsAction()
	{
		$sellerId = $this->getAuthUserId();
		$productsId = json_decode($this->request->get('products_id'));

		$request = new DeleteProductsRequest(
			$sellerId,
			$productsId
		);

		$service = new DeleteProductsService(
			$this->di->get('productRepository'),
			$this->di->get('productPhotosService')
		);

		try {
			$service->execute($request);

			$this->sendOk();
		} catch (\Exception $e) {
			$this->handleException($e);
		}
	}

	public function addVerifiedProduct()
	{
	}

	public function updateProductsStorefrontAction()
	{
		$sellerId = $this->getAuthUserId();
		$storefrontId = $this->dispatcher->getParam('storefront_id');
		$productsId = $this->request->get('products_id');

		$request = new UpdateProductsStorefrontRequest($productsId, $storefrontId, $sellerId);

		$service = new UpdateProductsStorefrontService(
			$this->di->get('productRepository')
		);

		try {
			$service->execute($request);

			$this->sendOk();
		} catch (\Exception $e) {
			$this->handleException($e);
		}
	}

	public function updateIsActiveProductsAction()
	{
		$sellerId = $this->getAuthUserId();
		$productsId = $this->request->get('products_id');

		$request = new UpdateIsActiveProductsRequest($sellerId, $productsId);

		$service = new UpdateIsActiveProductsService(
			$this->di->get('productRepository')
		);

		try {
			$service->execute($request);

			$this->sendOk();
		} catch (\Exception $e) {
			$this->handleException($e);
		}
	}
}
