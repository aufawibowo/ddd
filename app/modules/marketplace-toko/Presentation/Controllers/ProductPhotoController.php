<?php


namespace A7Pro\Marketplace\Toko\Presentation\Controllers;

use A7Pro\Marketplace\Toko\Core\Application\Services\DeleteProductPhotoById\DeleteProductPhotoByIdRequest;
use A7Pro\Marketplace\Toko\Core\Application\Services\DeleteProductPhotoById\DeleteProductPhotoByIdService;

class ProductPhotoController extends BaseController
{
    public function deleteProductPhotoAction()
    {
        $sellerId = $this->getAuthUserId();
        $photoId = $this->dispatcher->getParam('photo_id');

        $request = new DeleteProductPhotoByIdRequest($photoId, $sellerId);

        $service = new DeleteProductPhotoByIdService(
            $this->di->get('productPhotosRepository'),
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
