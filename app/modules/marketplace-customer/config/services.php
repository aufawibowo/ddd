<?php


use A7Pro\Marketplace\Customer\Infrastructure\Persistence\SqlCartRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Persistence\SqlInvoiceRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Persistence\SqlOrderRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Persistence\SqlProductPhotosRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Persistence\SqlProductRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Persistence\SqlProfileRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Persistence\SqlReviewPhotoRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Persistence\SqlReviewRepository;
use A7Pro\Marketplace\Customer\Infrastructure\Services\JwtTokenService;
use A7Pro\Marketplace\Customer\Infrastructure\Services\ProductPhotosService;
use A7Pro\Marketplace\Customer\Infrastructure\Services\ReviewPhotosService;

$container->setShared('tokenService', function () use ($container) {
    return new JwtTokenService($container->get('config'));
});

$container->setShared('cartRepository', function () use ($container) {
    return new SqlCartRepository($container->get('db'));
});

$container->setShared('invoiceRepository', function () use ($container) {
    return new SqlInvoiceRepository($container->get('db'));
});

$container->setShared('orderRepository', function () use ($container) {
    return new SqlOrderRepository($container->get('db'));
});

$container->setShared('productPhotosRepository', function () use ($container) {
    return new SqlProductPhotosRepository($container->get('db'));
});

$container->setShared('productRepository', function () use ($container) {
    return new SqlProductRepository($container->get('db'));
});

$container->setShared('profileRepository', function () use ($container) {
    return new SqlProfileRepository($container->get('db'));
});

$container->setShared('reviewPhotoRepository', function () use ($container) {
    return new SqlReviewPhotoRepository($container->get('db'));
});

$container->setShared('reviewRepository', function () use ($container) {
    return new SqlReviewRepository($container->get('db'));
});

$container->setShared('productPhotosService', function () use ($container) {
	return new ProductPhotosService($container->get('config'));
});

$container->setShared('reviewPhotosService', function () use ($container) {
    return new ReviewPhotosService($container->get('config'));
});
