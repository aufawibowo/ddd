<?php

return [
    // products
    [
        'pattern' => '/marketplace-toko/product',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'product',
        'action' => 'createProduct',
    ],
    [
        'pattern' => '/marketplace-toko/products',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'product',
        'action' => 'showProducts',
    ],
    [
        'pattern' => '/marketplace-toko/product/{product_id}',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'product',
        'action' => 'getSingleProduct',
    ],
    [
        'pattern' => '/marketplace-toko/product/{product_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'product',
        'action' => 'updateProduct',
    ],
    [
        'pattern' => '/marketplace-toko/products/delete',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'product',
        'action' => 'deleteProducts',
    ],
    [
        'pattern' => '/marketplace-toko/update-products-storefront/{storefront_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'product',
        'action' => 'updateProductsStorefront',
    ],
    [
        'pattern' => '/marketplace-toko/update-is-active-products',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'product',
        'action' => 'updateIsActiveProducts',
    ],

    // categories
    [
        'pattern' => '/marketplace-toko/categories',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'category',
        'action' => 'showCategories',
    ],

    // storefront
    [
        'pattern' => '/marketplace-toko/storefront',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'storefront',
        'action' => 'createStorefront',
    ],
    [
        'pattern' => '/marketplace-toko/storefronts',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'storefront',
        'action' => 'showStorefronts',
    ],
    [
        'pattern' => '/marketplace-toko/storefront/update/{storefront_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'storefront',
        'action' => 'updateStorefront',
    ],
    [
        'pattern' => '/marketplace-toko/storefront/delete/{storefront_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'storefront',
        'action' => 'deleteStorefront',
    ],

    // product photos
    [
        'pattern' => '/marketplace-toko/product-photos/delete/{photo_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'productPhoto',
        'action' => 'deleteProductPhoto',
    ],

    // orders
    [
        'pattern' => '/marketplace-toko/orders',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'order',
        'action' => 'showOrders',
    ],
    [
        'pattern' => '/marketplace-toko/orders/search',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'order',
        'action' => 'searchOrders',
    ],
    [
        'pattern' => '/marketplace-toko/order/{order_id}',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'order',
        'action' => 'showSingleOrder',
    ],
    [
        'pattern' => '/marketplace-toko/update-order-status/{order_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'order',
        'action' => 'updateOrderStatus',
    ],

    // couriers
    [
        'pattern' => '/marketplace-toko/couriers',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'courier',
        'action' => 'showCouriers',
    ],
    [
        'pattern' => '/marketplace-toko/couriers/update',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'courier',
        'action' => 'updateSellerCouriers',
    ],
    [
        'pattern' => '/marketplace-toko/couriers/checkout',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'courier',
        'action' => 'showCouriersOnCustomerCheckout',
    ],

    // profile
    [
        'pattern' => '/marketplace-toko/profile',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'sellerProfile',
        'action' => 'showSellerProfile',
    ],
    [
        'pattern' => '/marketplace-toko/profile/update',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'sellerProfile',
        'action' => 'updateSellerProfile',
    ],
    [
        'pattern' => '/marketplace-toko/profile/{profile_id}',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'sellerProfile',
        'action' => 'showSellerProfileById',
    ],

    // review
    [
        'pattern' => '/marketplace-toko/review/{review_id}',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'review',
        'action' => 'showSingleReview',
    ],
    [
        'pattern' => '/marketplace-toko/reviews',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'review',
        'action' => 'showReviews',
    ],
    [
        'pattern' => '/marketplace-toko/review/reply/{review_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'review',
        'action' => 'replyReview',
    ],

    // register
    [
        'pattern' => '/marketplace-toko/register-otp',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'registration',
        'action' => 'sellerOtp',
    ],
    [
        'pattern' => '/marketplace-toko/register/{phone}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'registration',
        'action' => 'sellerRegister',
    ],

    // login
    [
        'pattern' => '/marketplace-toko/login/otp',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'login',
        'action' => 'sellerOtp',
    ],
    [
        'pattern' => '/marketplace-toko/login',
        'method' => 'POST',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'login',
        'action' => 'sellerLogin',
    ],

    // get home data
    [
        'pattern' => '/marketplace-toko/home',
        'method' => 'GET',
        'namespace' => 'A7Pro\Marketplace\Toko\Presentation\Controllers',
        'controller' => 'Home',
        'action' => 'getHomeData',
    ],
];
