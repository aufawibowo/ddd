<?php

use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlCategoryRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlCourierRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlOrderRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlProductPhotosRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlProductRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlReviewRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlSellerRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlStorefrontRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlUserRepository;
// use A7Pro\Marketplace\Toko\Infrastructure\Persistence\SqlVerifiedProductRepository;
use A7Pro\Marketplace\Toko\Infrastructure\Services\GoSmsService;
use A7Pro\Marketplace\Toko\Infrastructure\Services\HashBasedOtpService;
use A7Pro\Marketplace\Toko\Infrastructure\Services\JwtTokenService;
use A7Pro\Marketplace\Toko\Infrastructure\Services\Md5UrlSignerService;
use A7Pro\Marketplace\Toko\Infrastructure\Services\RedisEventPublisher;
use A7Pro\Marketplace\Toko\Infrastructure\Services\ProductPhotosService;
use A7Pro\Marketplace\Toko\Infrastructure\Services\ProfilePicService;
use A7Pro\Marketplace\Toko\Infrastructure\Services\ReviewPhotosService;

$container->setShared('tokenService', function () use ($container) {
	return new JwtTokenService($container->get('config'));
});

$container->setShared('otpService', function () use($container) {
    return new HashBasedOtpService($container->get('config'));
});

$container->setShared('smsService', function () use($container) {
    return new GoSmsService($container->get('config'));
});

$container->setShared('urlSignerService', function () use($container) {
    return new Md5UrlSignerService($container->get('config'));
});

$container->set('eventPublisher', function () use ($container) {
	return new RedisEventPublisher($container->get('redis'));
});

$container->setShared('productPhotosService', function () use ($container) {
	return new ProductPhotosService($container->get('config'));
});

$container->setShared('reviewPhotosService', function () use ($container) {
	return new ReviewPhotosService($container->get('config'));
});

$container->setShared('profilePicService', function () use ($container) {
	return new ProfilePicService($container->get('config'));
});

$container->setShared('productRepository', function () use ($container) {
	return new SqlProductRepository($container->get('db'));
});

$container->setShared('orderRepository', function () use ($container) {
	return new SqlOrderRepository($container->get('db'));
});

$container->setShared('storefrontRepository', function () use ($container) {
	return new SqlStorefrontRepository($container->get('db'));
});

$container->setShared('categoryRepository', function () use ($container) {
	return new SqlCategoryRepository($container->get('db'));
});

$container->setShared('sellerRepository', function () use ($container) {
	return new SqlSellerRepository($container->get('db'));
});

$container->setShared('courierRepository', function () use ($container) {
	return new SqlCourierRepository($container->get('db'));
});

$container->setShared('productPhotosRepository', function () use ($container) {
	return new SqlProductPhotosRepository($container->get('db'));
});

// $container->setShared('verifiedProductRepository', function () use ($container) {
// 	return new SqlVerifiedProductRepository($container->get('db'));
// });

$container->setShared('reviewRepository', function () use ($container) {
	return new SqlReviewRepository($container->get('db'));
});

$container->setShared('userRepository', function () use($container) {
	return new SqlUserRepository($container->get('db'));
});
